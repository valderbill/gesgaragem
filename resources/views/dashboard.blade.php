@extends('layouts.app')

@section('content')
    <style>
       .img-destaque {
           width: 90%;             
           max-width: 2200px;       
           height: auto;           
           object-fit: contain;
           display: block;
           margin: 30px auto;     
       }



        .info-boxes {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 20px;
        }

        .info-box {
            padding: 20px;
            border-radius: 10px;
            background: #f8f9fa;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            min-width: 250px;
            text-align: center;
        }

        #clock {
            font-size: 2em;
            font-weight: bold;
        }

        #calendar {
            font-size: 1.2em;
        }

        #weather {
            font-size: 1.1em;
        }
    </style>

    <div class="container text-center">
        <img src="{{ asset('images/LISTA2.jpg') }}"
             alt="Imagem destaque"
             class="img-fluid rounded shadow-sm img-destaque">

        <div class="info-boxes">
            <div class="info-box">
                <div id="clock">--:--:--</div>
                <div>Horário atual (Brasília)</div>
            </div>

            <div class="info-box">
                <div id="calendar">--/--/----</div>
                <div>Data de hoje</div>
            </div>

            <div class="info-box">
                <div id="weather">Carregando clima...</div>
                <div>Clima - Simulado Brasília</div>
            </div>
        </div>
    </div>

    <script>
        function updateClockAndWeather() {
            const now = new Date();
            const brasiliaTime = new Date(now.toLocaleString("en-US", { timeZone: "America/Sao_Paulo" }));
            const hour = brasiliaTime.getHours();
            const timeStr = brasiliaTime.toLocaleTimeString('pt-BR');

            const dayNames = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
            const diaSemana = dayNames[brasiliaTime.getDay()];
            const dataStr = brasiliaTime.toLocaleDateString('pt-BR');

            document.getElementById('clock').textContent = timeStr;
            document.getElementById('calendar').textContent = `${dataStr} - ${diaSemana}`;

            let clima = '';
            let temperatura = '';

            if (hour >= 5 && hour < 11) {
                clima = 'Manhã ensolarada';
                temperatura = getRandomTemp(22, 26);
            } else if (hour >= 11 && hour < 16) {
                clima = 'Sol forte';
                temperatura = getRandomTemp(28, 32);
            } else if (hour >= 16 && hour < 19) {
                clima = 'Fim de tarde nublado';
                temperatura = getRandomTemp(24, 27);
            } else if (hour >= 19 && hour < 23) {
                clima = 'Noite fresca';
                temperatura = getRandomTemp(20, 23);
            } else {
                clima = 'Madrugada com neblina';
                temperatura = getRandomTemp(17, 20);
            }

            document.getElementById('weather').textContent = `${clima}, ${temperatura}°C`;
        }

        function getRandomTemp(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        setInterval(updateClockAndWeather, 1000);
        updateClockAndWeather();
    </script>
@endsection
