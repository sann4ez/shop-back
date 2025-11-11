<?php

namespace App\Http\Client\Api\Controllers;

use App\Http\Client\Api\Resources\Suggests\CountryResource;
use App\Http\Client\Api\Resources\Suggests\TermResource;
use App\Models\Location\Country;
use App\Models\Shop\Order;
use App\Models\Term;
use App\Support\Shippings\Novaposhta\Novaposhta;
use App\Support\Shippings\Ukrposhta\Ukrposhta;
use App\Support\Tracking\Drivers\Cainiao;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

final class SuggestController extends Controller
{
    /**
     * @apiPrivate
     * @api {get} /api/suggest/vocabularies Словники термінів таксономії
     * @apiVersion 1.0.0
     * @apiName SuggestVocabularies
     * @apiGroup Suggest
     *
     */
    public function vocabularies()
    {
        return response()->json([
            'data' => Term::vocabulariesList(),
        ]);
    }

    /**
     * @api {get} /api/suggest/terms/product_categories Категорії товарів
     * @apiVersion 1.0.0
     * @apiName SuggestProductCategories
     * @apiGroup Suggest
     *
     * @apiParam {String} [q] Пошуковий рядок
     * @apiParam {Integer} [limit=15] Ліміт
     * @apiParam {string} [parent_id] null - всі категорії, 0 - категорії першого рівня, `id` - підкатегорії з категорії id
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *     {
     *        "data": [
     *            {
     *               "id": "44681c9c-d405-4b1f-965e-e315798b4e3f",
     *               "slug": "ihrashky",
     *               "name": "Іграшки"
     *            },
     *            ...
     *         ]
     *     }
     */
    public function terms(Request $request, string $vocabulary)
    {
        if (!in_array($vocabulary, Term::vocabulariesList('slug'))) {
            abort(404);
        }

        $terms = Term::whereVocabulary($vocabulary)->with('translations')->filterable($request->all(), ['limit' => 15])->get();

        return TermResource::collection($terms);
    }

    /**
     * @api {get} /api/suggest/terms/post_categories Категорії статей
     * @apiVersion 1.0.0
     * @apiName SuggestPostCategories
     * @apiGroup Suggest
     *
     * @apiParam {String} [q] Пошуковий рядок
     * @apiParam {Integer} [limit=15] Ліміт
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *     {
     *        "data": [
     *            {
     *               "id": "64ca8a5b-2d20-487e-9182-78b862740812",
     *               "slug": "ihry-ta-dozvillia",
     *               "name": "Ігри та дозвілля"
     *            },
     *            ...
     *         ]
     *     }
     */

    /**
     * @api {get} /api/suggest/terms/brands Бренди
     * @apiVersion 1.0.0
     * @apiName SuggestBrands
     * @apiGroup Suggest
     *
     * @apiParam {String} [q] Пошуковий рядок
     * @apiParam {Integer} [limit=15] Ліміт
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *    {
     *       "data": [
     *           {
     *              "id": "27351b9d-4b55-411c-92cb-d9a5186e3374",
     *              "slug": "lego",
     *              "name": "Lego"
     *           },
     *           ...
     *        ]
     *    }
     */

    /**
     * @api {get} /api/suggest/terms/tags Теги
     * @apiVersion 1.0.0
     * @apiName SuggestTags
     * @apiGroup Suggest
     *
     * @apiParam {String} [q] Пошуковий рядок
     * @apiParam {Integer} [limit=15] Ліміт
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *    {
     *       "data": [
     *           {
     *              "id": "6540811a-1227-4488-a802-6ecfdb4296ad",
     *              "slug": "nature",
     *              "name": "Nature"
     *           },
     *           ...
     *        ]
     *    }
     */

    /**
     * @apiPrivate
     * @api {get} /api/suggest/countries 10. Країни
     * @apiVersion 1.0.0
     * @apiName SuggestCountries
     * @apiGroup Suggest
     *
     * @apiParam {String} [q] Пошукова строка
     * @apiParam {Integer} [limit=15] Ліміт
     *
     */
    public function countries(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string',
            'limit' => 'nullable|integer|between:0,255'
        ]);

        $countries = Country::when($q = $request->q, fn($b) => $b->where('name', 'LIKE', "%$q%")->orWhere('native', 'LIKE', "%$q%"))
            ->orderBy('name', 'asc')
            ->when($limit = $request->limit, fn($b) => $b->limit($limit))
            ->get();

        return CountryResource::collection($countries);
    }

    /**
     * @apiPrivate
     * @api {get} /api/suggest/tracker Трекер по ТТН
     * @apiVersion 1.0.0
     * @apiName SuggestTracker
     * @apiGroup Suggest
     *
     * @apiParam {String} ttn ТТН замовлення
     *
     */
    public function tracker(Request $request)
    {
        $request->validate(['ttn' => 'required|string|min:5|max:100']);
        $ttn = $request->ttn;

        if (!$request->user()) {
            $request->validate(['ttn' => 'required|string|min:5|max:100|exists:orders,ttn']);

            Order::whereTtn($ttn)->first();
        }

        $t = \Cache::remember("ttn.{$ttn}", 3600, fn() => (new Cainiao())->getData($ttn));

        if (!\Arr::get($t, 'origin_country')) {
            throw ValidationException::withMessages([
                'ttn' => ['The selected ttn is invalid.'],
            ]);
        }

        return response()->json([
            'data' => $t,
        ]);
    }

    /**
     * @api {get} /api/suggest/shipping/novaposhta/settlements 20. Нова пошта: Населені пункти
     * @apiVersion 1.0.0
     * @apiName SuggestNovaposhtaSettlements
     * @apiGroup Suggest
     *
     * @apiParam {String} [q] Назва населеного пункту
     *
     * @apiExample {curl} Example URL:
     *        /api/suggest/shipping/novaposhta/settlements?q=рожищ
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *   {
     *      "data": [
     *          {
     *              "Present": "м. Луцьк, Волинська обл.",
     *              "Warehouses": 419,
     *              "MainDescription": "Луцьк",
     *              "Area": "Волинська",
     *              "Region": "",
     *              "SettlementTypeCode": "м.",
     *              "Ref": "e71ab70b-4b33-11e4-ab6d-005056801329",
     *              "DeliveryCity": "db5c893b-391c-11dd-90d9-001a92567626",
     *              "AddressDeliveryAllowed": true,
     *              "StreetsAvailability": true,
     *              "ParentRegionTypes": "область",
     *              "ParentRegionCode": "обл.",
     *              "RegionTypes": "",
     *              "RegionTypesCode": ""
     *          },
     *          ...
     *      ],
     *  }
     */
    public function novaposhtaSettlements(Request $request)
    {
        $res = [];

        if ($q = $request->term ?: $request->q) {

            $api = new Novaposhta();
            $apiRes = $api->searchSettlements($q, 20);

            if (Arr::get($apiRes, 'success') === true) {
                $res = $apiRes['data'][0]['Addresses'] ?? [];
            }
        }

        return ['data' => $res];
    }

    /**
     * @api {get} /api/suggest/shipping/novaposhta/offices 21. Нова пошта: Відділення, Поштомати
     * @apiVersion 1.0.0
     * @apiName SuggestNovaposhtaOffices
     * @apiGroup Suggest
     *
     * @apiParam {String} [q] Пошук по назві відділення
     * @apiParam {Integer} [limit] Ліміт
     * @apiParam {String=office-відділення,locker-поштомат,all-всі} [type=all] Тип точки
     * @apiParam {String} CityRef Ідентифікатор міста (DeliveryCity з /settlements)
     *
     * @apiExample {curl} Example URL:
     *       /api/suggest/shipping/novaposhta/offices?CityRef=db5c893b-391c-11dd-90d9-001a92567626&type=office&q=№13&limit=5
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *   {
     *      "data": [
     *          {
     *              "SiteKey": "10830",
     *              "Description": "Відділення №142 (до 30 кг на одне місце): вул. Амвросія Бучми, 5",
     *              "DescriptionRu": "Отделение №142 (до 30 кг на одне місце): ул. Амвросия Бучмы, 5",
     *              "ShortAddress": "Київ, Амвросія Бучми, 5",
     *              "ShortAddressRu": "Киев, Амвросия Бучмы, 5",
     *              "Phone": "380800500609",
     *              "TypeOfWarehouse": "841339c7-591a-42e2-8233-7a0a00f0ed6f",
     *              "Ref": "d7108df0-8433-11e4-acce-0050568002cf",
     *              "Number": "142",
     *              "CityRef": "8d5a980d-391c-11dd-90d9-001a92567626",
     *              "CityDescription": "Київ",
     *              "CityDescriptionRu": "Киев",
     *              "SettlementRef": "e718a680-4b33-11e4-ab6d-005056801329",
     *              "SettlementDescription": "Київ",
     *              "SettlementAreaDescription": "Київська",
     *              "SettlementRegionsDescription": "",
     *              "SettlementTypeDescription": "місто",
     *              "SettlementTypeDescriptionRu": "город",
     *              "Longitude": "30.605018000000000",
     *              "Latitude": "50.431777000000000",
     *              "PostFinance": "0",
     *              "BicycleParking": "0",
     *              "PaymentAccess": "1",
     *              "POSTerminal": "1",
     *              "InternationalShipping": "1",
     *              "SelfServiceWorkplacesCount": "1",
     *              "TotalMaxWeightAllowed": "0",
     *              "PlaceMaxWeightAllowed": "30",
     *              "SendingLimitationsOnDimensions": {
     *                  "Width": 70,
     *                  "Height": 70,
     *                  "Length": 120
     *              },
     *              "ReceivingLimitationsOnDimensions": {
     *                  "Width": 70,
     *                  "Height": 70,
     *                  "Length": 120
     *              },
     *              "Reception": {
     *                  "Monday": "08:00-21:00",
     *                  "Tuesday": "08:00-21:00",
     *                  "Wednesday": "08:00-21:00",
     *                  "Thursday": "08:00-21:00",
     *                  "Friday": "08:00-21:00",
     *                  "Saturday": "09:00-19:00",
     *                  "Sunday": "09:00-19:00"
     *              },
     *              "Delivery": {
     *                  "Monday": "08:00-20:00",
     *                  "Tuesday": "08:00-20:00",
     *                  "Wednesday": "08:00-20:00",
     *                  "Thursday": "08:00-20:00",
     *                  "Friday": "08:00-20:00",
     *                  "Saturday": "09:00-19:00",
     *                  "Sunday": "09:00-19:00"
     *              },
     *              "Schedule": {
     *                  "Monday": "08:00-21:00",
     *                  "Tuesday": "08:00-21:00",
     *                  "Wednesday": "08:00-21:00",
     *                  "Thursday": "08:00-21:00",
     *                  "Friday": "08:00-21:00",
     *                  "Saturday": "09:00-19:00",
     *                  "Sunday": "09:00-19:00"
     *              },
     *              "DistrictCode": "Д11/В142",
     *              "WarehouseStatus": "Working",
     *              "WarehouseStatusDate": "2022-03-22 00:00:00",
     *              "WarehouseIllusha": "0",
     *              "CategoryOfWarehouse": "Branch",
     *              "Direct": "",
     *              "RegionCity": "КИЇВ СХІД ПОСИЛКОВИЙ",
     *              "WarehouseForAgent": "0",
     *              "GeneratorEnabled": "1",
     *              "MaxDeclaredCost": "0",
     *              "WorkInMobileAwis": "0",
     *              "DenyToSelect": "0",
     *              "CanGetMoneyTransfer": "0",
     *              "HasMirror": "0",
     *              "HasFittingRoom": "0",
     *              "OnlyReceivingParcel": "0",
     *              "PostMachineType": "",
     *              "PostalCodeUA": "02152",
     *              "WarehouseIndex": "11/142",
     *              "BeaconCode": "",
     *              "Location": ""
     *          },
     *      ]
     *   }
     */
    public function novaposhtaOffices(Request $request)
    {
        $request->validate(['q' => 'nullable|string', 'limit' => 'nullable|integer', 'CityRef' => 'required|string', 'type' => 'nullable|in:office,locker']);

        $api = new Novaposhta();

        $params['CityRef'] = $request->CityRef ?: $request->DeliveryCity ?: '';

        if ($q = $request->q) {
            $params['FindByString'] = $q;
        }
        if ($limit = $request->limit) {
            $params['Limit'] = $limit;
        }

        $types = $request->type;
        $apiRes = $api->getWarehousesByTypes(Arr::wrap($types), $params);

        return response()->json([
            'data' => $apiRes
        ]);
    }

    /**
     * @api {get} /api/suggest/shipping/ukrposhta/regions 30. Укрпошта: Області
     * @apiVersion 1.0.0
     * @apiName SuggestUkrposhtaRegions
     * @apiGroup Suggest
     *
     * @apiParam {String} q Назва області
     *
     * @apiExample {curl} Example URL:
     *        /api/suggest/shipping/ukrposhta/regions?q=Волин
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *   {
     *      "data": [
     *          {
     *              "REGION_ID": "263",
     *              "REGION_UA": "Волинська",
     *              "REGION_EN": "Volynska",
     *              "REGION_KATOTTG": "07000000000024379",
     *              "REGION_KOATUU": "700000000"
     *          },
     *          ...
     *       ]
     *   }
     */
    public function ukrposhtaRegions(Request $request)
    {
        $request->validate(['q' => 'nullable|string']);

        /** @var Ukrposhta $ukApi */
        $ukApi = app(Ukrposhta::class);

        $regionName = $request->q ?: $request->term;
        $res = $ukApi->getRegionsUkrposhta($regionName);

        return response()->json(['data' => $res['Entry'] ?? []]);
    }

    /**
     * @api {get} /api/suggest/shipping/ukrposhta/cities 31. Укрпошта: Населені пункти
     * @apiVersion 1.0.0
     * @apiName SuggestUkrposhtaCities
     * @apiGroup Suggest
     *
     * @apiParam {Integer} region_id Id області
     * @apiParam {String} q Назва населеного пункту
     *
     * @apiExample {curl} Example URL:
     *        /api/suggest/shipping/ukrposhta/cities?region_id=263&q=Луц
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *    {
     *       "data": [
     *          {
     *              "REGION_ID": "263",
     *              "POPULATION": "216505",
     *              "IS_DISTRICTCENTER": "1",
     *              "DISTRICT_ID": "300",
     *              "LONGITUDE": "25.383508",
     *              "CITY_KATOTTG": "07080170010083384",
     *              "CITY_RU": "0",
     *              "DISTRICT_EN": "LUTSKYI",
     *              "REGION_EN": "Volynska",
     *              "SHORTCITYTYPE_EN": null,
     *              "OLDCITY_RU": null,
     *              "CITYTYPE_UA": "місто",
     *              "OLDCITY_UA": null,
     *              "NEW_DISTRICT_UA": null,
     *              "CITY_EN": "Lutsk",
     *              "CITYTYPE_RU": null,
     *              "CITY_KOATUU": "0710100000",
     *              "REGION_RU": null,
     *              "NAME_UA": "Активний запис",
     *              "REGION_UA": "Волинська",
     *              "SHORTCITYTYPE_RU": null,
     *              "OLDCITY_EN": null,
     *              "CITY_ID": "3477",
     *              "DISTRICT_UA": "Луцький",
     *              "CITYTYPE_EN": "City",
     *              "SHORTCITYTYPE_UA": "м.",
     *              "LATTITUDE": "50.736758",
     *              "CITY_UA": "Луцьк",
     *              "OWNOF": "Луцька",
     *              "DISTRICT_RU": "0"
     *           },
     *           ...
     *        ]
     *    }
     */
    public function ukrposhtaCities(Request $request)
    {
        $request->validate(['q' => 'required|string', 'region_id' => 'required|string']);

        /** @var Ukrposhta $ukApi */
        $ukApi = app(Ukrposhta::class);

        $regionId = $request->region_id;
        $cityName = $request->q ?: $request->term;
        $res = $ukApi->getCitiesUkrposhta($regionId, $cityName);

        return response()->json(['data' => $res['Entry'] ?? []]);
    }

    /**
     * @api {get} /api/suggest/shipping/ukrposhta/departments 32. Укрпошта: Відділення
     * @apiVersion 1.0.0
     * @apiName SuggestUkrposhtaDepartments
     * @apiGroup Suggest
     *
     * @apiParam {Integer} [city_id] Id вибраного населеного пункту
     *
     * @apiExample {curl} Example URL:
     *        /api/suggest/shipping/ukrposhta/departments?city_id=263
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *    {
     *       "data": [
     *           {
     *              "LOCK_EN": "Active record",
     *              "CITY_UA_VPZ": "Луцьк",
     *              "POSTTERMINAL": "0",
     *              "POSTOFFICE_UA": "43002 Луцьк",
     *              "POSTCODE": "43002",
     *              "ISAUTOMATED": "1",
     *              "PHONE": "+380-800-300-545",
     *              "LONGITUDE": "25.37949",
     *              "CITY_KATOTTG": "07080170010083384",
     *              "STREET_UA_VPZ": "просп. Відродження, 12",
     *              "IS_SECURITY": "0",
     *              "POSTOFFICE_ID": "178083",
     *              "POSTOFFICE_UA_DETAILS": null,
     *              "POSTINDEX": "43002",
     *              "LOCK_UA": "Активний запис",
     *              "CITY_UA_TYPE": "м.",
     *              "CITY_VPZ_KATOTTG": "07080170010083384",
     *              "LOCK_CODE": "0",
     *              "CITY_VPZ_ID": "3477",
     *              "CITY_KOATUU": "0710100000",
     *              "STREET_ID_VPZ": "40459",
     *              "LOCK_RU": "Активная запись",
     *              "CITY_VPZ_KOATUU": "0710100000",
     *              "TYPE_ACRONYM": "МВ",
     *              "TYPE_LONG": "Міське відділення",
     *              "TYPE_ID": "48",
     *              "CITY_ID": "3477",
     *              "IS_NOLETTERS": "0",
     *              "HOUSENUMBER": "12",
     *              "LATTITUDE": "50.74884",
     *              "CITY_UA": "Луцьк"
     *           },
     *           ...
     *        ]
     *    }
     */
    public function ukrposhtaDepartments(Request $request)
    {
        $request->validate(['city_id' => 'required|string']);

        /** @var Ukrposhta $ukApi */
        $ukApi = app(Ukrposhta::class);

        $res = $ukApi->getDepartmentUkrposhta($request->city_id);

        return response()->json(['data' => $res['Entry'] ?? []]);
    }
}
