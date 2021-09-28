<?php

namespace App\WebService;

class Correios{

    /**
     * URL base da API
     * @var string
     */
    const URL_BASE = 'http://ws.correios.com.br';

    /**
     * Códigos de serviço dos Correios
     * @var string
     */
    const SERVICO_SEDEX = '04014';
    const SERVICO_SEDEX_12 = '04782';
    const SERVICO_SEDEX_10 = '04790';
    const SERVICO_SEDEX_HOJE = '04804';
    const SERVICO_PAC = '04510';

    /**
     * Códigos dos formatos dos Correios
     * @var integer
     */
    const FORMATO_CAIXA_PACOTE = 1;
    const FORMATO_ROLO_PRISMA = 2;
    const FORMATO_ENVELOPE = 3;

    /**
     * Código da empresa com contrato
     * @var string
     */
    private $codigoEmpresa = '';

    /**
     * Senha da empresa com contrato
     * @var string
     */
    private $senhaEmpresa = '';

    /**
     * Método responsável pela definição de dados de contrato do webservice dos Correios
     * @param string $codigoEmpresa
     * @param string $senhaEmpresa
     */
    public function __construct($codigoEmpresa = '', $senhaEmpresa = ''){
        $this->codigoEmpresa = $codigoEmpresa;
        $this->senhaEmpresa = $senhaEmpresa;
    }

    /**
     * Método responsável por calcular o frete nos Correios
     *
     * @param string $codigoServico
     * @param string $cepOrigem
     * @param string $cepDestino
     * @param float $peso
     * @param integer $formato
     * @param integer $comprimento
     * @param integer $altura
     * @param integer $largura
     * @param integer $diametro
     * @param boolean $maoPropria
     * @param integer $valorDeclarado
     * @param boolean $avisoRecebimento
     * @return object
     */
    public function calcularFrete(
    $codigoServico,
    $cepOrigem, 
    $cepDestino,
    $peso,
    $formato,
    $comprimento,
    $altura,
    $largura,
    $diametro = 0,
    $maoPropria = false,
    $valorDeclarado = 0,
    $avisoRecebimento = false){

    //PARÂMETROS DA URL DE CÁLCULO
    $parametros = [
        'nCdEmpresa' => $this->codigoEmpresa,
        'sDsSenha' => $this->senhaEmpresa,
        'nCdServico' => $codigoServico,
        'sCepOrigem' => $cepOrigem,
        'sCepDestino' => $cepDestino,
        'nVlPeso' => $peso,
        'nCdFormato' => $formato,
        'nVlComprimento' => $comprimento,
        'nVlAltura' => $altura,
        'nVlLargura' => $largura,
        'nVlDiametro' => $diametro,
        'sCdMaoPropria' => $maoPropria ? 'S' : 'N',
        'nVlValorDeclarado' => $valorDeclarado,
        'sCdAvisoRecebimento' => $avisoRecebimento ? 'S' : 'N',
        'StrRetorno' => 'xml'
    ];

    //QUERY
    $query = http_build_query($parametros);

    //EXECUTA A CONSULTA DE FRETE
    $resultado = $this->get('/calculador/CalcPrecoPrazo.aspx?' .$query);
    
    //RETORNA OS DADOS DO FRETE CALCULADO
    return $resultado ? $resultado->cServico : null;
    
    }

    /**
     * Método responsável por executar a consulta get no webservice dos Correios
     *
     * @param string $resource
     * @return object
     */
    public function get($resource){
        //ENDPOINT COMPLETO
        $endpoint = self::URL_BASE.$resource;

        //INICIA O CURL
        $curl = curl_init();

        //CONFIGURAÇÕES DO CURL
        curl_setopt_array($curl, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ]);

        //EXECUTA A CONSULTA CURL
        $response = curl_exec($curl);

        //FECHA A CONEXÃO DO CURL
        curl_close($curl);

        //RETORNA O XML INSTANCIADO
        return strlen($response) ? simplexml_load_string($response) : null;
    }
}