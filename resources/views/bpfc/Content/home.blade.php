@extends('bpfc.Layout.app')

@section('head','navbar','sidebar')

@section('contentheader')

<div class="content-header">
      <div class="container-fluid">
      </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
<section class="content">
    {{-- Header --}}
    <div class="first_content container-fluid">
        <header class="container-fluid">
            <div class="d-flex justify-content-between">
                <div class="d-flex mt-4">
                    <img src="{{ asset('dist/img/icon.ico') }}" class="img-fluid" alt="Responsive image" style= "width:55px; height:55px;">
                    <h6 class="company-description mt-3 ml-1" style="font-family:Century Gothic; color: #354785 ; font-size:15px;">Parkland World Indonesia 2</h6>
                </div>
                <div class="d-flex mt-4">
                    <img src="{{ asset('dist/img/PngItem_269848.png') }}" class="img-fluid" alt="Responsive image" style= "width:95px; height:55px;">
                </div>
            </div>
        </header>
        <main class="main_container container-fluid">
            <div class="row mt-5">
                <div class="col-6 align-self-center">
                    <div class="d-flex justify-content-center">
                        <div class="card_title card col-10">
                            <div class="card-body">
                                <h1 class="title-home" style="font-family:poppins-extrabold;">STOCKFITTING</h1>
                                <h1 class="title-home2 " style="font-family:poppins-semibold;">Bonding Process Flow Chart</h1>
                                <div class="col-lg-12 col-md-12 col-sm-12 mt-5">
                                    <div class="box">
                                        <div class="container-1">
                                            <span class="icon"><i class="fa fa-search"></i></span>
                                            <input type="text" class="form-controller text-center" list="image-list" id="searchData" name="search" placeholder="Search" autocomplete="off" style="border-radius: 30px; font-family:poppins-regular; border-color:rgb(155, 154, 153);">
                                            <datalist id="image-list">
                                                <option value="MS-WS 327 v1"></option>
                                                <option value="510 V6 STF REG_001"></option>
                                                <option value="520V8 LONG STF_001"></option>
                                                <option value="574 2D PUR GR_001"></option>
                                                <option value="860V13 STF REG_001"></option>
                                                <option value="CCS 990 v3_001"></option>
                                                <option value="CCS 990 v6_001"></option>
                                                <option value="K990 V6 REG STF_001"></option>
                                                <option value="M-RVEL LWSR_REG_001"></option>
                                                <option value="ML 610 V1 STF REG_001"></option>
                                                <option value="NW 574 V1 STF REG_001"></option>
                                                <option value="U574LG REG GR_001"></option>
                                                <option value="UA 600 - SF_REG_001"></option>
                                                <option value="WL-996 EVA 3PCS LONG_001"></option>
                                                <option value="WCHCOCO V2 PERFORMANCE (W2)"></option>
                                                <option value="520 V9"></option>
                                          </datalist>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card_title card col-10 ml-5">
                        <div class="card-body">
                            <button id="btn_show_page">
                                <a type="button" class="list-group-item list-group-item-action" href="#id_second_content"><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex justify-content-center">
                        <div class="d-flex">
                            <img src="{{ asset('dist/img/bpfc_logo.png') }}" class="image-company img-fluid" alt="Responsive image" >
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    {{-- Modal --}}
    <div class="modal fade" id="showImage" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" style="max-width: 90%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-tasks" aria-hidden="true"></i> Bonding Process Flow Chart</h5>
            </div>
            <div class="modal-body">
                <div id="id_place_image">
                </div>
            </div>
        </div>
    </div>
</section>

@section('second_content')
<section class="second_content" id="id_second_content">
    <div class="slide_content container-fluid">
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    <div class="card_title card">
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="logo-company d-flex">
                                    <h1 class="title-second-content text_center mt-2" style="font-family:poppins-bold;">STOCKFITTING</h1>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="logo-company d-flex">
                                    <h1 class="title-second-content2 text-center">Bonding Process Flow Chart</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="d-flex justify-content-center">
                                <img class="img_carousel" src="{{ asset('dist/img/BPFC 327 buat LA H2 2022_001.png') }}" alt="First slide" onclick="zoomPicture();">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="d-flex justify-content-center">
                                <img class="img_carousel" src="{{ asset('dist/img/510 V6 STF REG_001.png') }}" alt="Second slide">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="d-flex justify-content-center">
                                <img class="img_carousel" src="{{ asset('dist/img/520V8 LONG STF_001.png') }}" alt="Third slide">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="d-flex justify-content-center">
                                <img class="img_carousel" src="{{ asset('dist/img/574 2D PUR GR_001.png') }}" alt="Third slide">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="d-flex justify-content-center">
                                <img class="img_carousel" src="{{ asset('dist/img/574 KIDS_STF REG_ZB_001.png') }}" alt="Third slide">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="d-flex justify-content-center">
                                <img class="img_carousel" src="{{ asset('dist/img/860V13 STF REG_001.png') }}" alt="Third slide">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="d-flex justify-content-center">
                                <img class="img_carousel" src="{{ asset('dist/img/CCS 990 v3_001.png') }}" alt="Third slide">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="d-flex justify-content-center">
                                <img class="img_carousel" src="{{ asset('dist/img/CCS 990 v6_001.png') }}" alt="Third slide">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="d-flex justify-content-center">
                                <img class="img_carousel" src="{{ asset('dist/img/K990 V6 REG STF_001.png') }}" alt="Third slide">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="d-flex justify-content-center">
                                <img class="img_carousel" src="{{ asset('dist/img/M-RVEL LWSR_REG_001.png') }}" alt="Third slide">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="d-flex justify-content-center">
                                <img class="img_carousel" src="{{ asset('dist/img/ML 610 V1 STF REG_001.png') }}" alt="Third slide">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="d-flex justify-content-center">
                                <img class="img_carousel" src="{{ asset('dist/img/NW 574 V1 STF REG_001.png') }}" alt="Third slide">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="d-flex justify-content-center">
                                <img class="img_carousel" src="{{ asset('dist/img/U574LG REG GR_001.png') }}" alt="Third slide">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="d-flex justify-content-center">
                                <img class="img_carousel" src="{{ asset('dist/img/UA 600 - SF_REG_001.png') }}" alt="Third slide">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="d-flex justify-content-center">
                                <img class="img_carousel" src="{{ asset('dist/img/WL-996 EVA 3PCS LONG_001.png') }}" alt="Third slide">
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
