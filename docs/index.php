<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPWA Maker</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        .output {
            margin-top: 20px;
        }
        pre {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            position: relative;
        }
        .copy-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }
        .output-section {
            display: none;
        }
        .step {
            margin-bottom: 20px;
        }
        footer {
            background-color: #f8f9fa;
            padding: 20px 0;
            text-align: center;
        }
        main {
            padding-top: 100px;
            padding-bottom: 100px;
        }
    </style>
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1>EasyPWA Maker</h1>
    </header>
    <main class="container mt-5">
        <h2 class="mb-4">Gerador de PWA</h2>
        <form id="pwaForm" method="POST" action="">
            <div class="step">
                <h4>Passo 1: Informações Básicas</h4>
                <p>Preencha as informações básicas do seu aplicativo.</p>
                <div class="form-group">
                    <label for="siteUrl">URL do Site</label>
                    <input type="url" class="form-control" id="siteUrl" name="siteUrl" placeholder="https://example.com" required>
                </div>
                <div class="form-group">
                    <label for="appTitle">Título do APP</label>
                    <input type="text" class="form-control" id="appTitle" name="appTitle" placeholder="Meu PWA" required>
                </div>
                <div class="form-group">
                    <label for="appDescription">Descrição do APP</label>
                    <textarea class="form-control" id="appDescription" name="appDescription" rows="3" placeholder="Descrição do aplicativo" required></textarea>
                </div>
            </div>
            <div class="step">
                <h4>Passo 2: Ícones</h4>
                <p>Forneça os URLs dos ícones do aplicativo.</p>
                <div class="form-group">
                    <label for="icon192">Ícone 192x192 (URL)</label>
                    <input type="url" class="form-control" id="icon192" name="icon192" placeholder="https://example.com/icon-192x192.png" required>
                </div>
                <div class="form-group">
                    <label for="icon512">Ícone 512x512 (URL)</label>
                    <input type="url" class="form-control" id="icon512" name="icon512" placeholder="https://example.com/icon-512x512.png" required>
                </div>
            </div>
            <div class="step">
                <h4>Passo 3: Cores</h4>
                <p>Escolha as cores de fundo e do tema do aplicativo.</p>
                <div class="form-group">
                    <label for="backgroundColor">Cor de Fundo</label>
                    <input type="color" class="form-control" id="backgroundColor" name="backgroundColor" value="#ffffff" required>
                </div>
                <div class="form-group">
                    <label for="themeColor">Cor do Tema</label>
                    <input type="color" class="form-control" id="themeColor" name="themeColor" value="#000000" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Gerar Arquivos</button>
        </form>
        
        <div class="output output-section mt-5">
            <h3>Código do Manifesto</h3>
            <pre id="manifestOutput"><button class="copy-button" data-target="manifestOutput">Copiar</button></pre>

            <h3>Código do Service Worker</h3>
            <pre id="swOutput"><button class="copy-button" data-target="swOutput">Copiar</button></pre>

            <h3>Instruções de Instalação</h3>
            <div id="instructionsOutput"></div>
        </div>
    </main>
    <footer>
        EasyPWA Maker <?php echo date('Y'); ?> | Todos os direitos reservados araujodev.com.br
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#pwaForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                $.ajax({
                    type: 'POST',
                    url: 'api.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        const result = JSON.parse(response);
                        $('#manifestOutput').text(result.manifest);
                        $('#swOutput').text(result.serviceWorker);
                        $('#instructionsOutput').html(result.instructions);
                        $('.output-section').show();
                    }
                });
            });

            $(document).on('click', '.copy-button', function() {
                const targetId = $(this).data('target');
                const textToCopy = document.getElementById(targetId).innerText;
                const tempTextarea = document.createElement('textarea');
                tempTextarea.value = textToCopy;
                document.body.appendChild(tempTextarea);
                tempTextarea.select();
                document.execCommand('copy');
                document.body.removeChild(tempTextarea);
                alert('Código copiado para a área de transferência!');
            });
        });
    </script>
</body>
</html>
