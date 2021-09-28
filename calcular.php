<?php 

require __DIR__.'/vendor/autoload.php';

use \App\WebService\Correios;

//NOVA INSTÂNCIA DOS CORREIOS SEM CONTRATO
$obCorreios = new Correios();

//DADOS PARA O CÁLCULO DO FRETE
$codigoServico = Correios::SERVICO_SEDEX;
$cepOrigem = '79112-150';
$cepDestino = '60830-564';
$peso = 1;
$formato = 1;
$comprimento = 15;
$altura = 15;
$largura = 15;
$diametro = 0;
$maoPropria = false;
$valorDeclarado = 0;
$avisoRecebimento = false;

//EXECUTA O CÁLCULO DE FRETE
$frete = $obCorreios->calcularFrete(
    $codigoServico,
    $cepOrigem, 
    $cepDestino,
    $peso,
    $formato,
    $comprimento,
    $altura,
    $largura,
    $diametro,
    $maoPropria,
    $valorDeclarado,
    $avisoRecebimento);

    //VERIFICA O RESULTADO
    if(!$frete){
        die('Problemas ao calcular o frete');
    }

    //VERIFICA O ERRO
    if(strlen($frete->MsgErro)){
        die('Erro: '.$frete->MsgErro);
    }

    //IMPRIME OS DADOS DA CONSULTA
    echo "CEP Origem: ".$cepOrigem."\n";
    echo "CEP Destino: ".$cepDestino."\n";
    echo "Valor: ".$frete->Valor."\n";
    echo "Prazo: ".$frete->PrazoEntrega."\n";
