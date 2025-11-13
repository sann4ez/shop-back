<?php

namespace App\Http\Client\Api\Controllers;

use App\Support\Shippings\Novaposhta\Novaposhta;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
final class SuggestController extends Controller
{
    /**
     * @api {get} /api/suggest/shipping/novaposhta/settlements 01. Нова пошта: Населені пункти
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
     * @api {get} /api/suggest/shipping/novaposhta/offices 02. Нова пошта: Відділення, Поштомати
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
}
