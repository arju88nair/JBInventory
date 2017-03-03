<!DOCTYPE html>
<html lang="en">
<head>
    <title>Just Books Inventory</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css">

</head>

<body>

<div class="wrapper animated bounce">
    <h1>Just Books</h1>
    <hr>
    <p>
        {{ $errors->first('email') }}
        {{ $errors->first('password') }}
    </p>

    <form role="form" method="post" action="{{ action('HomeController@doLogin') }}">
        <label id="icon" for="username"><i class="fa fa-user"></i></label>
        <input type="text" placeholder="email" id="username" name="email">
        <label id="icon" for="password"><i class="fa fa-key"></i></label>
        <input type="password" placeholder="Password" id="password" name="password">
        <input type="submit" value="Sign In">
        <hr>

    </form>
</div>


</body>
<style>/*Fonts*/
    @import 'https://fonts.googleapis.com/css?family=Open+Sans';
    @import 'https://fonts.googleapis.com/css?family=Galada';

    ::selection {
        background: #ffb7b7; /* WebKit/Blink Browsers */
    }
    ::-moz-selection {
        background: #ffb7b7; /* Gecko Browsers */
    }
    * {
        /*-moz-box-sizing: border-box;*/
        /*-webkit-box-sizing: border-box;*/
        box-sizing: border-box;
    }
    :focus {outline:none}
    /*Reset*/
    body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6,
    pre, form, fieldset, input, textarea, p, blockquote, th, td {
        padding:0;
        margin:0;}
    body,input{
        font-family:'Open sans',sans-serif;
        font-size:18px;
        color:#4c4c4c;
    }
    body{
        background:url({{ asset('img/unnamed.png') }})  no-repeat center center fixed;

        background-size: cover;
    }
    form{
        margin: 10px 35px;
    }
    input{
        border:none;
    }
    a{
        text-decoration: none;
        color: rgb(255, 255, 255);

    }
    a:hover{
        color: rgba(255, 152, 0, 0.79);
        text-decoration: underline;

    }
    input[type=text], input[type=password] {
        width: 200px;
        height: 38px;
        border:1px solid #cbc9c9;
        padding-left:5px;
        margin-left:-5px;
        margin-top:3px;
        border-radius:0px 3px 3px 0px;
        /*-webkit-border-radius:0px 3px 3px 0px;*/
        /*-moz-border-radius:0px 3px 3px 0px;*/
    }
    input[type=submit]{
        width: 237px;
        height: 40px;
        margin-left:17px;
        border-radius:3px;
        background-color:#ae6a6a;
        color:#f8f8f8;
        border-radius:2px 2px 12px 12px;


    }
    input[type=submit]:hover{
        background-color:#607d8b;
        color:#f8f8f8;
        cursor:pointer;

    }
    #icon{
        background-color:#F4F4F4;
        color:#625864;
        display:inline-block;
        font-size:14px;
        padding-top:10px;
        padding-bottom:7px;
        width:40px;
        margin-left: 15px;
        margin-bottom:20px;
        text-align:center;
        border-top:solid 1px #cbc9c9;
        border-bottom:solid 1px #cbc9c9;
        border-left:solid 1px #cbc9c9;
        border-radius:3px 0 0 3px;
        /*-webkit-border-radius:3px 0 0 3px;*/
        /*-moz-border-radius:3px 0 0 3px;*/
    }
    .wrapper{
        margin:50px auto;
        width: 343px;
        height: 280px;
        border-radius:5px;
        -moz-border-radius:5px;
        -webkit-border-radius:5px;
    }
    .wrapper h1{
        font-family: 'Galada', cursive;
        color:#f4f4f4;
        letter-spacing:8px;
        text-align:center;
        padding-top:5px;
        padding-bottom:5px;
    }
    .wrapper hr{
        opacity:0.2;

    }
    .crtacc{
        margin-left:75px;
    }</style>
</html>
