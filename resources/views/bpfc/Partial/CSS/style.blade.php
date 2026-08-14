<style>
    body {
        font-family: "Karla", sans-serif;
        font-size: 1rem;
        background-image: url("dist/img/11.jpg");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        width: 100%;
        height: 100%;
    }
    #btn_show_page{
        background: transparent;
        border: transparent;
    }
    .fa-chevron-down{
        animation: slideInDown;
        animation-duration: 1.2s; 
        animation-iteration-count: infinite;
    }
    .modal-title{
        font-family: poppins-regular;
        color:rgb(169, 196, 193);
    }
    .list-group-item{
        background: linear-gradient(90deg, rgba(154,249,233,1) 0%, rgba(151,234,163,1) 100%);
        font-family: poppins-semibold;
        font-size: 20px; 
        border: transparent;
        color: black;
        box-shadow: 1px 3px 4px rgb(151,234,163);
        margin-left: 15px;
    }
    .container-1{
        width: 300px;
        vertical-align: middle;
        white-space: nowrap;
        position: relative;
    }
    .container-1 input#searchModalPO{
        width: 300px;
        height: 35px;
        background: whitesmoke;
        font-size: 10pt;
        float: left;
        color: #63717f;
        padding-left: 15px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        margin-left: 20px;
    }
    .container-1 input#searchData{
        width: 400px;
        height: 50px;
        background: whitesmoke;
        font-size: 10pt;
        float: left;
        color: #63717f;
        padding-left: 15px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
    }
    .container-1 .iconModalPo{
        position: absolute;
        top: 50%;
        margin-left: 30px;
        margin-top: 6px;
        z-index: 1;
        color: #4f5b66;
    }
    .container-1 .icon{
        position: absolute;
        top: 50%;
        margin-left: 17px;
        margin-top: 12px;
        z-index: 1;
        color: #4f5b66;
    }
    /* width */
    ::-webkit-scrollbar {
      width: 10px;
    }   

    /* Track */
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }   

    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: rgba(169, 196, 193);
    }   

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
      background: rgb(44, 117, 109);
    }
    .slide_content{
        background-image: url("dist/img/10.jpg");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        /* background-color: rgba(169, 196, 193); */
        width: 100%;
        height: 100%;
        color: black;
    }
    .carousel-caption{
        font-family: poppins-semibold;
        color: black;
        
    }
    .img_carousel{
        width: 65%;
        height: auto;
        filter:drop-shadow(0px 0px 20px #363636);
    }
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        height: 10px;
        width: 10px;
        outline: black;
        background-image: none;
    }   

    .carousel-control-next-icon:after
    {
        content: '>';
        font-size: 30px;
        color: black;
        text-shadow: 1px 3px 2px rgb(155, 155, 155);
    }   

    .carousel-control-prev-icon:after {
        content: '<';
        font-size: 30px;
        color: black;
        text-shadow: 1px 3px 2px rgb(155, 155, 155);
    }
    .IncomingbtnBlock{
        /* background: linear-gradient(90deg, rgba(154,249,233,1) 0%, rgba(169, 196, 193,1) 100%); */
        background-color: rgba(169, 196, 193);
        transition: 0.5s;
        width: 100%;
        height: auto;
        margin: 4px 2px;
        font-size: 120%;
        text-decoration: none;
        display: inline-block;
        -webkit-transition-duration: 0.4s; /* Safari */
        transition-duration: 0.4s;
        box-shadow: 1px 3px 4px rgba(169, 196, 193,1);
        color: rgba(24,36,66,1);
        border-radius: 25px;
        font-family: poppins-semibold;
    } 
    .IncomingbtnBlock:hover {
        background-position: right center; /* change the direction of the change here */
        color: rgb(253, 253, 253);
        text-decoration: none;
        box-shadow: 1px 3px 4px rgb(255, 255, 255);
        text-shadow: 1px 3px 2px rgb(155, 155, 155);
    }
    .card{
        background: transparent;
        border: transparent;
    }
    .imgTamplate{
        width: 100%;
        max-width: 200px;
        height: auto;
        position: absolute;
        top: 10px
    }
    .title-home{
        margin-top:0%;
        color:rgb(0, 0, 0);  
        font-size: 100px;
    }
    .title-home2{
        margin-top:0%;
        color:rgb(0, 0, 0);
        font-size: 100px;
    }
    .middle_section_container{
        margin-top:25%;
        margin-bottom: 10%; 
    }
    .company-description{
        font-family:Century Gothic;
        font-size:20px;
        color: rgb(0, 0, 0);
    }
    .main_container{
        line-height: 28px;
        transform: translateY(600px);
        animation: slideUp .9s ease-in-out forwards .0s;
        overflow: hidden;
        width: 100%;
        height: 100%;
    }
    .image-company{
        width: 90%;
    }
    .card_title{
        background-color: transparent;
        border-color: transparent;
    }

    @keyframes slideIn {
        0% {
          transform: translateX(500px) scale(.2);
        }
        100% {
          transform: translateX(0px) scale(1);
        }
      }

    @keyframes slideUp {
        0% {
          transform: translateY(600px);
        }
        100% {
          transform: translateY(0px);
        }
    }

    @keyframes expand {
        0% {
          transform: translateX(1400px);
        }
        100% {
          transform: translateX(0px);
        }
    }

    @media (min-width: 374.00px) and (max-width: 813.00px) {
        .middle_section_container{
            margin-top:65%;
            margin-bottom: 10%; 
        }
        .title-home{
            margin-top:0%;
            color: white;
            font-size: 60px;
        }
        .title-home2{
            margin-top:0%;
            color:white;
            font-size: 60px;
        }
    }

    @media (max-width: 575.98px) {
        .middle_section_container{
            margin-top:65%;
            margin-bottom: 10%; 
        }
        .title-home{
            margin-top:0%;
            color: white;
            font-size: 60px;
        }
        .title-home2{
            margin-top:0%;
            color: white;
            font-size: 60px;
        }
        .IncomingbtnBlock{
            background: linear-gradient(90deg, rgba(154,249,233,1) 0%, rgba(151,234,163,1) 100%);
            transition: 0.5s;
            width: 100%;
            height: auto;
            margin: 4px 2px;
            font-size: 100%;
            text-decoration: none;
            display: inline-block;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            box-shadow: 1px 3px 4px rgb(105, 105, 105);
            color: rgba(24,36,66,1);
            border-radius: 25px;
            font-family: poppins-semibold;
            
        } 
        .IncomingbtnBlock:hover {
            background-position: right center; /* change the direction of the change here */
            color: rgb(253, 253, 253);
            text-decoration: none;
            text-shadow: 1px 3px 20px rgb(155, 155, 155);
        }
        .QCbtnBlock{
            /* background-image: linear-gradient(to right, #77A1D3 0%, #79CBCA  51%, #77A1D3  100%); */
            background: linear-gradient(90deg, rgba(154,249,233,1) 0%, rgba(151,234,163,1) 100%);
            transition: 0.5s;
            width: 100%;
            height: auto;
            margin: 4px 2px;
            font-size: 100%;
            text-decoration: none;
            display: inline-block;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            box-shadow: 1px 3px 4px rgba(169, 196, 193,1);
            color: rgba(24,36,66,1);
            border-radius: 25px;
            font-family: poppins-semibold;

        } 
        .QCbtnBlock:hover {
            background-position: right center; /* change the direction of the change here */
            background-color: #ffffff;
            color: rgb(253, 253, 253);
            text-decoration: none;
            text-shadow: 1px 3px 2px rgb(155, 155, 155);
        }
        .SummarybtnBlock{
            /* background-image: linear-gradient(to right, #77A1D3 0%, #79CBCA  51%, #77A1D3  100%); */
            background: linear-gradient(90deg, rgba(154,249,233,1) 0%, rgba(151,234,163,1) 100%);
            transition: 0.5s;
            width: 100%;
            height: auto;
            margin: 4px 2px;
            font-size: 100%;
            text-decoration: none;
            display: inline-block;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            box-shadow: 1px 3px 4px rgba(169, 196, 193,1);
            color: rgba(24,36,66,1);
            border-radius: 25px;
            font-family: poppins-semibold;

        } 
        .SummarybtnBlock:hover {
            background-position: right center; /* change the direction of the change here */
            color: rgb(253, 253, 253);
            text-decoration: none;
            text-shadow: 1px 3px 2px rgb(155, 155, 155);

        }
    }
    @media (min-width: 576px) and (max-width: 767.98px) {
        .middle_section_container{
            margin-top:65%;
            margin-bottom: 10%; 
        }
        .title-home{
            margin-top:0%;
            color:white;
            font-size: 60px;
        }
        .title-home2{
            margin-top:0%;
            color:white;
            font-size: 60px;
        }
        .IncomingbtnBlock{
            /* background-image: linear-gradient(to right, #77A1D3 0%, #79CBCA  51%, #77A1D3  100%); */
            background: linear-gradient(90deg, rgba(154,249,233,1) 0%, rgba(151,234,163,1) 100%);
            transition: 0.5s;
            width: 100%;
            height: auto;
            margin: 4px 2px;
            font-size: 95%;
            text-decoration: none;
            display: inline-block;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            box-shadow: 1px 3px 4px rgba(169, 196, 193,1);
            color: rgba(24,36,66,1);
            border-radius: 25px;
            font-family: poppins-semibold;
            
        } 
        .IncomingbtnBlock:hover {
            background-position: right center; /* change the direction of the change here */
            color: rgb(253, 253, 253);
            text-decoration: none;
            text-shadow: 1px 3px 2px rgb(155, 155, 155);
        }
        .QCbtnBlock{
            /* background-image: linear-gradient(to right, #77A1D3 0%, #79CBCA  51%, #77A1D3  100%); */
            background: linear-gradient(90deg, rgba(154,249,233,1) 0%, rgba(151,234,163,1) 100%);
            transition: 0.5s;
            width: 100%;
            height: auto;
            margin: 4px 2px;
            font-size: 95%;
            text-decoration: none;
            display: inline-block;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            box-shadow: 1px 3px 4px rgba(169, 196, 193,1);
            color: rgba(24,36,66,1);
            border-radius: 25px;
            font-family: poppins-semibold;

        } 
        .QCbtnBlock:hover {
            background-position: right center; /* change the direction of the change here */
            color: rgb(253, 253, 253);
            text-decoration: none;
            text-shadow: 1px 3px 2px rgb(155, 155, 155);
        }
        .SummarybtnBlock{
            /* background-image: linear-gradient(to right, #77A1D3 0%, #79CBCA  51%, #77A1D3  100%); */
            background: linear-gradient(90deg, rgba(154,249,233,1) 0%, rgba(151,234,163,1) 100%);
            transition: 0.5s;
            width: 100%;
            height: auto;
            margin: 4px 2px;
            font-size: 95%;
            text-decoration: none;
            display: inline-block;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            box-shadow: 1px 3px 4px rgba(169, 196, 193,1);
            color: rgba(24,36,66,1);
            border-radius: 25px;
            font-family: poppins-semibold;

        } 
        .SummarybtnBlock:hover {
            background-position: right center; /* change the direction of the change here */
            color: rgb(253, 253, 253);
            text-decoration: none;
            text-shadow: 1px 3px 2px rgb(155, 155, 155);
        }
    }
    @media (min-width: 768px) and (max-width: 991.98px) {
        .middle_section_container{
            margin-top:65%;
            margin-bottom: 50%; 
        }
        .title-home{
            margin-top:0%;
            color:white;
            font-size: 65px;
        }
        .title-home2{
            margin-top:0%;
            color:white;
            font-size: 65px;
        }
        .IncomingbtnBlock{
            /* background-image: linear-gradient(to right, #77A1D3 0%, #79CBCA  51%, #77A1D3  100%); */
            background: linear-gradient(90deg, rgba(154,249,233,1) 0%, rgba(151,234,163,1) 100%);
            transition: 0.5s;
            width: 100%;
            height: auto;
            margin: 4px 2px;
            font-size: 110%;
            text-decoration: none;
            display: inline-block;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            box-shadow: 1px 3px 4px rgba(169, 196, 193,1);
            color: rgba(24,36,66,1);
            border-radius: 25px;
            font-family: poppins-semibold;
        } 
        .IncomingbtnBlock:hover {
            background-position: right center; /* change the direction of the change here */
            color: rgb(253, 253, 253);
            text-decoration: none;
            text-shadow: 1px 3px 2px rgb(155, 155, 155);
        }
        .QCbtnBlock{
            /* background-image: linear-gradient(to right, #77A1D3 0%, #79CBCA  51%, #77A1D3  100%); */
            background: linear-gradient(90deg, rgba(154,249,233,1) 0%, rgba(151,234,163,1) 100%);
            transition: 0.5s;
            width: 100%;
            height: auto;
            margin: 4px 2px;
            font-size: 110%;
            text-decoration: none;
            display: inline-block;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            box-shadow: 1px 3px 4px rgba(169, 196, 193,1);
            color: rgba(24,36,66,1);
            border-radius: 25px;
            font-family: poppins-semibold;
        } 
        .QCbtnBlock:hover {
            background-position: right center; /* change the direction of the change here */
            color: rgb(253, 253, 253);
            text-decoration: none;
            text-shadow: 1px 3px 2px rgb(155, 155, 155);
        }
        .SummarybtnBlock{
            /* background-image: linear-gradient(to right, #77A1D3 0%, #79CBCA  51%, #77A1D3  100%); */
            background: linear-gradient(90deg, rgba(154,249,233,1) 0%, rgba(151,234,163,1) 100%);
            transition: 0.5s;
            width: 100%;
            height: auto;
            margin: 4px 2px;
            font-size: 110%;
            text-decoration: none;
            display: inline-block;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            box-shadow: 1px 3px 4px rgba(169, 196, 193,1);
            color: rgba(24,36,66,1);
            border-radius: 25px;
            font-family: poppins-semibold;
        } 
        .SummarybtnBlock:hover {
            background-position: right center; /* change the direction of the change here */
            color: rgb(253, 253, 253);
            text-decoration: none;
            text-shadow: 1px 3px 2px rgb(155, 155, 155);

        }
    }
    @media (min-width: 992px) and (max-width: 1199.98px) {
        .middle_section_container{
            margin-top:10%;
            margin-bottom: 10%; 
        }
        .title-home{
            margin-top:0%;
            color:white;
            font-size: 90px;
        }
        .title-home2{
            margin-top:0%;
            color:white;
            font-size: 90px;
        }
        .IncomingbtnBlock{
            /* background: linear-gradient(90deg, rgba(154,249,233,1) 0%, rgba(151,234,163,1) 100%); */
            transition: 0.5s;
            width: 50%;
            height: auto;
            margin: 4px 2px;
            font-size: 100%;
            text-decoration: none;
            display: inline-block;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            box-shadow: 1px 3px 4px rgb(138, 138, 138);
            color: rgba(24,36,66,1);
            border-radius: 25px;
            font-family: poppins-semibold;
        } 
        .IncomingbtnBlock:hover {
            background-position: right center; /* change the direction of the change here */
            color: rgb(253, 253, 253);
            text-decoration: none;
            text-shadow: 1px 3px 2px rgb(155, 155, 155);
        }
        .QCbtnBlock{
            /* background-image: linear-gradient(to right, #77A1D3 0%, #79CBCA  51%, #77A1D3  100%); */
            background: linear-gradient(90deg, rgba(154,249,233,1) 0%, rgba(151,234,163,1) 100%);
            transition: 0.5s;
            width: 100%;
            height: auto;
            margin: 4px 2px;
            font-size: 120%;
            text-decoration: none;
            display: inline-block;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            box-shadow: 1px 3px 4px rgba(169, 196, 193,1);
            color: rgba(24,36,66,1);
            border-radius: 25px;
            font-family: poppins-semibold;
        } 
        .QCbtnBlock:hover {
            background-position: right center; /* change the direction of the change here */
            color: rgb(253, 253, 253);
            text-decoration: none;
            text-shadow: 1px 3px 2px rgb(155, 155, 155);
        }
        .SummarybtnBlock{
            /* background-image: linear-gradient(to right, #77A1D3 0%, #79CBCA  51%, #77A1D3  100%); */
            background: linear-gradient(90deg, rgba(154,249,233,1) 0%, rgba(151,234,163,1) 100%);
            transition: 0.5s;
            width: 100%;
            height: auto;
            margin: 4px 2px;
            font-size: 120%;
            text-decoration: none;
            display: inline-block;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            box-shadow: 1px 3px 4px rgba(169, 196, 193,1);
            color: rgba(24,36,66,1);
            border-radius: 25px;
            font-family: poppins-semibold;
        } 
        .SummarybtnBlock:hover {
            background-position: right center; /* change the direction of the change here */
            color: rgb(253, 253, 253);
            text-decoration: none;
        }
    }

    @media (min-width: 1200px) {
        .middle_section_container{
            margin-top:10%;
            margin-bottom: 10%; 
        }
        .title-home{
            margin-top:0%;
            color:rgb(169, 196, 193);
            text-shadow: 2px 5px 4px rgb(169, 196, 193);
            font-size: 85px;
        }
        .title-second-content{
            margin-top:0%;
            color:rgb(255, 255, 255);
            font-size: 70px;
            text-shadow: 4px 6px 6px rgb(169, 196, 193);
        }
        .title-second-content2{
            margin-top:0%;
            color:rgb(41, 41, 41);
            text-shadow: 1px 1px 1px rgb(117, 128, 126);
            font-size: 50px;
            font-family: poppins-semibold;
        }
        .title-home2{
            margin-top:0%;
            color:rgb(41, 41, 41);
            font-size: 60px;
            /* text-shadow: 2px 3px 2px rgb(77, 67, 75); */
        }
        
    }
</style>