<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use DateTime;
use DateTimeZone;

class VisualController extends Controller{
    public function searchImage(Request $request){
        $search_image = $request->search;
        $image = '';

        if ($search_image == 'MS-WS 327 v1'){
            $image = '<img src="dist/img/BPFC 327 buat LA H2 2022_001.png" class="img-fluid" alt="Responsive image" width=150%;>';
        }else if ($search_image == '510 V6 STF REG_001'){
            $image = '<img src="dist/img/510 V6 STF REG_001.png" class="img-fluid" alt="Responsive image" width=100%;>';
        }else if ($search_image == '520V8 LONG STF_001'){
            $image = '<img src="dist/img/520V8 LONG STF_001.png" class="img-fluid" alt="Responsive image" width=100%;>';
        }else if ($search_image == '574 2D PUR GR_001'){
            $image = '<img src="dist/img/574 2D PUR GR_001.png" class="img-fluid" alt="Responsive image" width=100%;>';
        }else if ($search_image == '860V13 STF REG_001'){
            $image = '<img src="dist/img/860V13 STF REG_001.png" class="img-fluid" alt="Responsive image" width=100%;>';
        }else if ($search_image == 'CCS 990 v3_001'){
            $image = '<img src="dist/img/CCS 990 v3_001.png" class="img-fluid" alt="Responsive image" width=100%;>';
        }else if ($search_image == 'CCS 990 v6_001'){
            $image = '<img src="dist/img/CCS 990 v6_001.png" class="img-fluid" alt="Responsive image" width=100%;>';
        }else if ($search_image == 'K990 V6 REG STF_001'){
            $image = '<img src="dist/img/K990 V6 REG STF_001.png" class="img-fluid" alt="Responsive image" width=100%;>';
        }else if ($search_image == 'M-RVEL LWSR_REG_001'){
            $image = '<img src="dist/img/M-RVEL LWSR_REG_001.png" class="img-fluid" alt="Responsive image" width=100%;>';
        }else if ($search_image == 'ML 610 V1 STF REG_001'){
            $image = '<img src="dist/img/ML 610 V1 STF REG_001.png" class="img-fluid" alt="Responsive image" width=100%;>';
        }else if ($search_image == 'NW 574 V1 STF REG_001'){
            $image = '<img src="dist/img/NW 574 V1 STF REG_001.png" class="img-fluid" alt="Responsive image" width=100%;>';
        }else if ($search_image == 'U574LG REG GR_001'){
            $image = '<img src="dist/img/U574LG REG GR_001.png" class="img-fluid" alt="Responsive image" width=100%;>';
        }else if ($search_image == 'UA 600 - SF_REG_001'){
            $image = '<img src="dist/img/UA 600 - SF_REG_001.png" class="img-fluid" alt="Responsive image" width=100%;>';
        }else if ($search_image == 'WL-996 EVA 3PCS LONG_001'){
            $image = '<img src="dist/img/WL-996 EVA 3PCS LONG_001.png" class="img-fluid" alt="Responsive image" width=100%;>';
        }else if ($search_image == "WCHCOCO V2 PERFORMANCE (W2)") {
            $image = '<img src="dist/img/coco v2.png" class="img-fluid" alt="Responsive image" width=100%;>';
        }else if ($search_image == "520 V9") {
            $image = '<img src="dist/img/520 v9.png" class="img-fluid" alt="Responsive image" width=100%;>';
        }

        $data = array(
            'img' => $image,
        );
        return response()->json($data);
    }
}
