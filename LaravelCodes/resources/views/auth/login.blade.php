<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <title>Login</title>
    <style>
       body,
    html {
      margin: 0;
      padding: 0;
    }

    input[type="email"],
    input[type="password"] {
      background-color: rgb(219, 216, 216);
      width: 300px;
      padding: 10px;
      border: transparent;
      border-radius: 20px;
      transition: border 0.3s ease, box-shadow 0.3s ease;
      margin: 5px;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
      border: 1px solid rgb(116, 116, 116);
      outline: none;
      box-shadow: 0 4px 3px rgba(0, 0, 0, 0.1);
    }

    input[type="email"]:hover,
    input[type="password"]:hover {
      box-shadow: 0 4px 3px rgba(0, 0, 0, 0.1);
    }

    input[type="submit"] {
      width: 110px;
      padding: 10px;
      margin-top: 30px;
      font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
      background-color: #008080;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 95%;
      transition: all 0.3s ease;
      animation: fadeIn 0.5s ease forwards;
      animation-delay: 0.7s;
      position: relative;
      left: 33%;
    }

    input[type="submit"]:hover {
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    a {
      text-align: center;
      display: block;
      margin-top: 10px;
      color: black;
      font-size: 14px;
      opacity: 0;
      animation: fadeIn 0.5s ease forwards;
      animation-delay: 1s;
    }

    a:hover {
      text-decoration: underline;
    }

    /* Animation Keyframes */
    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    .background {
        background-image: url("{{ asset('images/login.jpg') }}");
        background-size: cover;
        background-position: center;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center; /* Center the container vertically and horizontally */
    }

    .container {
      width: 800px;
      height: 500px;
      border-radius: 20px;
      background-color: #f0f0f0;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      align-items: center; /* Ensure content inside the container is vertically centered */
      overflow: hidden;
      box-shadow: 0 1px 20px rgba(0, 0, 0, 0.3);
    }

    .left-column {
      width: 45%;
      height: 100%;
      background: linear-gradient(to right,
          #0f6969,
          #008080,
          #019c9c,
          #01b3b3);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .left-column div:first-child {
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      font-size: 30px;
      letter-spacing: 5px;
      color: whitesmoke;
      display: inline-block;
    }

    .left-column div:nth-child(2) {
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      font-size: 13px;
      color: whitesmoke;
      text-align: center;
      margin-top: 35px;
    }

    .sign-up a {
      background-color: transparent;
      color: whitesmoke;
      border: 1px solid whitesmoke;
      padding: 13px 40px;
      transition: all 0.5s ease;
      border-radius: 30px;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
      margin-top: 30px;
    }

    .sign-up a:hover {
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .home-text a {
      color: whitesmoke;
      font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
      display: inline;
      font-size: 11px;
      text-decoration: none;
    }

    .home-text a:hover {
      text-decoration: underline;
    }

    .right-column {
      width: 55%;
      height: 100%;
      background: linear-gradient(to right, white, whitesmoke);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    .right-column div:first-child {
      color: #008080;
      font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
      letter-spacing: 2px;
      font-size: 30px;
    }

    a {
      text-decoration: none;
      padding: 0 7px 0 7px;
    }

    a:hover {
      text-decoration: none;
    }

    .information-container {
      width: 80%;
      height: 200px;
      margin-top: 30px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .forget-password a {
      color: gray;
      font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
      font-size: 12px;
      margin-top: 17px;
    }

    .forget-password a:hover {
      text-decoration: underline;
    }

    .login a {
        background-color: transparent;
        color: whitesmoke;
        border: 1px solid whitesmoke;
        padding: 10px 30px;
        border-radius: 20px;
        text-decoration: none;
        font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
            "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-block;
        margin-top: 20px;
    }

    .login a:hover {
        background-color: whitesmoke;
        color: #008080;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }
    </style>
</head>

<body>
    <div class="background">
        <div class="container">
            <div class="left-column">
                <div>Hello, Friend!</div>
                <div>Enter your personal details and<br />share your journey with us</div>
                <div class="sign-up"><a href="{{ route('register') }}">Sign up</a></div>
                <hr style="width: 250px; margin-top: 60px" />
                <div>
                    <i class="fas fa-arrow-left" style="color: whitesmoke; font-size: 12px"></i>
                    <div class="home-text" style="display: inline">
                        <a href="{{ url('/homepage') }}">Cancel and return to home page</a>
                    </div>
                </div>
            </div>
            <div class="right-column">
                <div>Continue your journey!</div>
                <div class="information-container">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <label for="email"></label>
                        <div>
                            <input type="email" id="email" name="email" placeholder="Email" required />
                        </div>
                        <label for="password"></label>
                        <div>
                            <input type="password" id="password" name="password" placeholder="Password" required />
                        </div>
                        <div class="forget-password">
                            <a href="{{ route('forgot-password') }}">Forgot Password?</a>
                        </div>
                        <input type="submit" name="submit" value="Log In" />
                    </form>
                </div>
                @if ($errors->any())
                    <div style="color:red; margin-top: 10px;">{{ $errors->first() }}</div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>