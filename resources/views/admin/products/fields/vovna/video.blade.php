<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                {!! Lte3::lfmFile('fields[video]', isset($model) ? $model->getFields('video') : null, ['label' => 'Відеофайл']) !!}
            </div>
            <div class="col-md-6">
                {!! Lte3::lfmImage('fields[video_img]', isset($model) ? $model->getFields('video_img') : null, ['label' => 'Превю відео']) !!}
            </div>
        </div>
    </div>
</div>
