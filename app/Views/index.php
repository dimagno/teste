<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário em Linha</title>
    <!-- Link do CSS do Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center">
    <main>

        <div class="mt-3">
            <h2 class=text-center><?php echo $title ?></h2>

            <!-- Formulário em Linha -->
            <form action="#" method="POST" class="form-inline" id="form">
                <input type="hidden" value="0" id="idd">
                <div class="form-group col-6 ">
                    <input type="text" class="form-control mb-1" id="cpf" name="cpf" placeholder="Digite o CPF" required style="width:100%">
                </div>

                <div class="form-group  col-6">
                    <input type="text" class="form-control mb-1" id="creci" name="creci" placeholder="Digite o CRECI" required style="width:100%">
                </div>

                <div class="form-group col-12 mb-1">
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o Nome" required style="width:100%">
                </div>

                <button type="submit" class="btn btn-dark btn-enviar" style="width:100%">Enviar</button>
            </form>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-dark table-striped   rounded" id="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Creci</th>
                        <th scope="col">CPF</th>
                        <th class="text-center" colspan=2> actions</th>
                    </tr>
                </thead>
                <tbody>


                    <!-- Adicione mais linhas conforme necessário -->
                </tbody>
            </table>


        </div>
    </main>

    <!-- Script do Bootstrap (opcional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


    <!-- Inclua o jQuery Mask Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

</body>

</html>

<script>
    $(document).ready(function(e) {
        $('#table').dataTable()
        listar();
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        $('#cpf').mask('000.000.000-00')
        $('#creci').mask('00000-ZZ/AA', {
            translation: {
                'Z': {
                    pattern: /[A-Za-z]/
                }, // Letra (F ou J, por exemplo)
                'A': {
                    pattern: /[A-Za-z]/
                } // Siglas de estados (ex: SP, RJ)
            }
        });



        function editar(element) {
            var td = $(element).closest('td');

            // Pega o <tr> que contém esse <td>
            var tr = $(td).closest('tr');
            var id = tr.find('td:eq(0)').data('id');
            console.log("ID:" + id)
            var nome = tr.find('td:eq(1)').text(); // Nome
            var creci = tr.find('td:eq(2)').text(); // Creci
            var cpf = tr.find('td:eq(3)').text(); // CPF
            $('#idd').val(id);
            $('#nome').val(nome)
            $('#creci').val(creci)
            $('#cpf').val(cpf)
            $('#nome').focus()
            $('.btn-enviar').text('Salvar')
            $('.btn-enviar').addClass('btn-salvar')
            $('.btn-enviar').removeClass('btn-enviar')

        }


        function validate(flag = null) {
            message = "";
            let isValid = true;
            let cpf = $('#cpf').val()
            let nome = $('#nome').val()
            let creci = $('#creci').val()
            if (nome.length < 2) {
                message += 'O nome deve ter pelo menos 2 caracteres <br>';
                isValid = false;
            }
            cpf = cpf.replace(/\D/g, '')
            if (cpf.length !== 11) {
                message += 'O CPF deve conter 11 caracteres <br>';
                isValid = false;
            }
            creci = creci.trim();
            if (creci.length < 2) {
                isValid = false;
                message += 'o CRECI deve conter pelo menos 2 caracteres'
            }
            if (!isValid) {
                Toast.fire({
                    icon: "error",
                    title: message,
                    timer: 5000,
                });
            } else {
                console.log("formulario ok");
                let formData = {
                    nome: nome,
                    cpf: cpf,
                    creci: creci

                }
                if (!flag)
                    cadastrar(formData)
                else
                    return isValid


            }
        }

        function listar() {
            $.ajax({
                url: '/teste/listar',
                type: 'GET'
            }).done(function(data) {
                console.log("consulta de corretores realizada:" + data)

                let table = $('#table tbody')
                table.empty()
                let linha = ""
                let dados = JSON.parse(data)
                console.log(dados);

                $.each(dados, function(key, value) {
                    console.log("value:" + value);
                    var linha = '<tr><td data-id="' + value.id + '">' + value.id + '</td><td>' + value.nome + '</td><td>' + value.creci + '</td><td>' + value.cpf + '</td><td><button class="btn btn-primary editar-btn">Editar</button></td><td><button class="btn btn-danger remover-btn">Remover</button></td></tr>';

                    table.append(linha)
                })
                $(document).on('click', '.editar-btn', function() {
                    editar(this); // Chama a função editar passando o botão
                });
            }).fail(function(error) {
                console.log("ERROR D: " + error)
            })
        }

        function cadastrar(formData) {
            console.log('chamando ajax')
            $.ajax({
                url: '/teste/cadastrar',
                data: formData,
                type: "POST"
            }).done(function(data) {
                console.log(" done: " + data)
                $('#cpf').val('')
                $('#creci').val('')
                $('#nome').val('')
                Toast.fire({
                    icon: "success",
                    title: "Corretor Cadastrado com sucesso"
                });
                listar()
            }).fail(function(error) {
                console.log("Error: " + error)
            })
        }

        function atualizar(element) {

            var id = $('#idd').val(); // ID
            var nome = $('#nome').val() // Nome
            var creci = $('#creci').val() // Creci
            var cpf = $('#cpf').val(); // CPF

            formData = {
                id: id,
                nome: nome,
                creci: creci,
                cpf: cpf
            }
            console.log("ID:" + id)
            $.ajax({
                url: '/teste/atualizar',
                data: formData,
                type: "POST"
            }).done(function(data) {
                console.log(" data: " + JSON.stringify(data))
                $('#cpf').val('')
                $('#creci').val('')
                $('#nome').val('')
                Toast.fire({
                    icon: "success",
                    title: "Corretor atualizado com sucesso"
                });
                listar()
                $('.btn-salvar').text('Enviar')
                $('.btn-salvar').addClass('btn-enviar')
                $('.btn-salvar').removeClass('btn-salvar')
            }).fail(function(error) {
                console.log("Error: " + error)
            })

        }
        $(document).on('click', '.btn-salvar', function(e) {
            e.preventDefault()
            if (validate(1)) {
                // Alterar as classes do botão
                $('.btn-enviar').addClass('btn-salvar');
                $('.btn-enviar').removeClass('btn-enviar');
                atualizar($(this))
            }
        });

        // Delegação para o botão com a classe .btn-enviar
        $(document).on('click', '.btn-enviar', function(e) {
            console.log('click enviar');
            e.preventDefault();

            validate();
        });
        $(document).on('click', '.remover-btn', function(e) {
            Swal.fire({
                title: "Tem certeza que deseja remover esse corretor?",
                text: "você não poderá reverter isso depois!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim, apagar!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "deletado!",
                        text: "corretor removido com sucesso",
                        icon: "success"
                    });
                    
             remover($(this))
                }
            });
        })

        function remover(element) {
            var td = $(element).closest('td');
            // Pega o <tr> que contém esse <td>
            var tr = $(td).closest('tr')
            var id = tr.find('td:eq(0)').data('id')
            $.ajax({
                url: '/teste/remover',
                data: {
                    id: id
                },
                type: 'POST'

            }).done(function(data) {
                console.log("removido")
                Toast.fire({
                    icon: "success",
                    title: "Usuário removido com sucesso!",
                    timer: 4000,
                })
                listar()
            }).fail(function(error) {
                console.log("err:" + error)
            })

        }

    })
</script>