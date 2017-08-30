<?php
//função para cadastrar um contato;
function cadastrar($nome,$email,$telefone){
    //Pega o arquivo "contatos.json", decodifica e retorna todos os contatos;
    $contatosAuxiliar = pegarContatos();
    //A variável $contato recebe os parâmetros enviados atraveś do formulário.
    $contato = [
        'id'      => $id,
        'nome'    => $nome,
        'email'   => $email,
        'telefone'=> $telefone
    ];
    //Array_push pega o $contato e coloca no final do $contatosAuxiliar, que é o arquivo "contatos.json" decodificado;
    array_push($contatosAuxiliar, $contato);
    //Atualizo o arquivo;
    atualizarArquivo($contatosAuxiliar);
}

//Função pegarContatos pega todos os contatos do arquivo contatos.json;
function pegarContatos($valor_buscado = null){

    if ($valor_buscado == null){
        //Pegar arquivo "contatos.json";
        $contatosAuxiliar = file_get_contents('contatos.json');
        //decodificar o arquivo;
        $contatosAuxiliar = json_decode($contatosAuxiliar, true);
        //retornar o arquivo;
        return $contatosAuxiliar;
    } else {
        return buscarContato($valor_buscado);
    }
}

//Função para excluir os contatos;
function excluirContato($id){
     //Chamo a função para pegar os contatos;
    $contatosAuxiliar = pegarContatos();
    //Para cada contatoAuxiliar, eu pego o dado do contato na posição que está e...;
    foreach ($contatosAuxiliar as $posicao => $contato){
    //Se a a variável id (['id']) do contato é igual a variável id que estou procurando...
        if($id == $contato['id']) {
    //excluir os dados do contato pelo id;
            unset($contatosAuxiliar[$posicao]);
        }
    }

    atualizarArquivo($contatosAuxiliar);
}
//Função para editar o contato;
function editarContato($id){
    //Pego os contatos;
    $contatosAuxiliar = pegarContatos();
    //Para cada contatoAuxiliar como contato...;
    foreach ($contatosAuxiliar as $contato){
     //Se e o id do contato é o mesmo que estou procurando
        if ($contato['id'] == $id){
     //retorne para mim o contato com seus dados (que irá aparecer na página editar.php)
            return $contato;
        }
    }
}
//Função para Salvar o contato que foi editado;
function salvarContatoEditado($id){
    //Pego os contatos
    $contatosAuxiliar = pegarContatos();
    //Para cada contatoAuxiliar como a posição do array contato...;
    foreach ($contatosAuxiliar as $posicao => $contato){
    //Se o id do contato é o id que estou procurando...;
        if ($contato['id'] == $id){
        //Então posso editar os dados do contato;
            $contatosAuxiliar[$posicao]['nome'] = $_POST['nome'];
            $contatosAuxiliar[$posicao]['email'] = $_POST['email'];
            $contatosAuxiliar[$posicao]['telefone'] = $_POST['telefone'];
            break;
        }
    }
    //Atualiza o arquivo;
    atualizarArquivo($contatosAuxiliar);
}
//Função para Atualizar o arquivo;
function atualizarArquivo($contatosAuxiliar){
    //Após ter cadastrado/editado/excluído o usuário, o arquivo "contatos.json" é codificado novamente;
    $contatosJson = json_encode($contatosAuxiliar, JSON_PRETTY_PRINT);
    //recebe todos os dados de usuário no arquivo "contatos.json", substituindo os dados que haviam anteriormente;
    file_put_contents('contatos.json', $contatosJson);
    //Redireciona para página inicial;
    header("Location: index.phtml");
}
//Função para buscar um contato pelo nome;
function buscarContato($nome){
    //Pego os contatos;
    $contatosAuxiliar = pegarContatos();

    $contatosEncontrados = [];

    //Para cada contatoAuxiliar como contato...;
    foreach ($contatosAuxiliar as $contato){
        //Se e o id do contato é o mesmo que estou procurando
        if ($contato['nome'] == $nome){
            //retorne para mim o contato com seus dados;
            $contatosEncontrados[] = $contato;
        }
    }

    return $contatosEncontrados;
}
//ROTAS
if ($_GET['acao'] == 'cadastrar') {
    cadastrar($_POST['nome'], $_POST['email'], $_POST['telefone']);
    } elseif ($_GET['acao'] == 'editar') {
    salvarContatoEditado($_POST['id'], $_POST['nome'], $_POST['email'], $_POST['telefone']);
    } elseif ($_GET['acao'] == 'cadastrar') {
    excluirContato($_GET['id']);
    }