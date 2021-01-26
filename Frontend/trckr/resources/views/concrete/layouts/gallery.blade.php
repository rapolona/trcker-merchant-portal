<!DOCTYPE html>
<html class="page-small-footer" lang="en" url="{{ asset('/') }}">
<head>
    <title>Hustle Portal Gallery</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('components/base/base.css') }}">
    <script data-loaded="true" src="{{ asset('./components/base/jquery-3.4.1.min.js') }}"></script> <!-- IMPORTANT DONT CHANGE -->
    <script data-loaded="true" src="{{ asset('./components/base/jquery-ui.min.js') }}"></script> <!-- IMPORTANT DONT CHANGE -->
    <script src="{{ asset('components/base/script.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
</head>
<body>
<div class="page">
    <section class="section-lg">
        <div class="container">
          <div class="row row-30">
            <div class="col-12">
              <div class="panel">
                <div class="panel-header">
                  <div class="panel-title">Gallery Group</div>
                </div>
                <div class="panel-body">
                  <div class="row row-30" data-lightgallery>
                  	@foreach($gallery as $image)
                    <div class="col-sm-6 col-lg-4"><a class="lightgallery-item" href="{{ $image }}"><img src="{{ $image }}" width="400" height="300" alt=""></a></div>
                    @endforeach
                    @if(count($gallery) < 1)
                    <h3>No images</h3>
                    @endif
                  </div>
                </div>
              </div>
               @include('concrete.layouts.pagination')  
            </div>

          </div>
        </div>
      </section>
</div>
</body>
</html>
