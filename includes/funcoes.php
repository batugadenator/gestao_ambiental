<?php

/**
 * Converte o formato dd/mm/aaaa para aaaammdd para salvar no banco de dados
 * @param string $data (dd/mm/aaaa)
 * @return string (aaaammdd)
 */
function data_para_banco($data) {
    $data = substr($data, 6, 4) . substr($data, 3, 2) . substr($data, 0, 2);
    return $data;

}

/**
 * converte a hora hhmm para hh:mm para exibir para o usuário
 * @param string $hora (hhmm)
 * @return string (hh:mm)
 */
function numero_para_hora($hora) {
    $hora = substr($hora, 0, 2) . ":" . substr($hora, 2, 2);
    return $hora;

}

/**
 * Converte o formato aaammdd para dd/mm/aaaa para exibir ao usuário
 * @param string $data (aaammdd)
 * @return string (dd/mm/aaaa)
 */
function banco_para_data($data) {
    $data = str_replace('-', '', $data);
    if (!empty($data)) {
        $data = substr($data, 6, 2) . "/" . substr($data, 4, 2) . "/" . substr($data, 0, 4);
    }

    return $data;

}

/**
 * Converte o formato numerico 1999.999 para 1.999,999 acrecendo ou diminuindo casas decimais 
 * conforme informado no parametro 2 para exibição ao usuário
 * @param type $valor (1999.999)
 * @param type $decimais (default = 2)
 * @return type (1.999,99)
 */
function banco_para_numero($valor, $decimais = 2) {
    if (empty($valor)) {
        $valor = 0;
    }
    $valor = number_format((float) ($valor), $decimais, ',', '.');
    return $valor;

}

/**
 * Converte o formato numerico 1.999,999 para 1999.999 acrecendo ou diminuindo casas decimais
 * conforme informado no parametro 2 para salvar em banco de dados
 * @param float $valor (1.999,999)
 * @param number $decimais (default = 2)
 * @return float (1999.99)
 */
function numero_para_banco($valor, $decimais = 2) {
    if ($valor == "" and $valor<>0) {
        return "null";
    }
    $valor = preg_replace('/[^0-9,.,-]*/', '', $valor);
    $valor = number_format(floatval(str_replace(',', '.', str_replace('.', '', trim($valor)))), $decimais, '.', '');
    return $valor;

}

/**
 * Retorna a data por extenso de acordo com os parÂmetros informados
 * @param string $mes
 * @param string $dia
 * @param string $ano
 * @return string 
 */
function data_para_texto($mes, $dia = "", $ano = "") {

    // configuração mes
    switch ($mes) {
        case 1: $mes = "Janeiro";
            break;
        case 2: $mes = "Fevereiro";
            break;
        case 3: $mes = "Março";
            break;
        case 4: $mes = "Abril";
            break;
        case 5: $mes = "Maio";
            break;
        case 6: $mes = "Junho";
            break;
        case 7: $mes = "Julho";
            break;
        case 8: $mes = "Agosto";
            break;
        case 9: $mes = "Setembro";
            break;
        case 10: $mes = "Outubro";
            break;
        case 11: $mes = "Novembro";
            break;
        case 12: $mes = "Dezembro";
            break;
    }

    if ($dia == "") {
        if ($ano == "") {
            return($mes);
        } else {
            return ("$mes de $ano");
        }
    }

    if ($ano == "") {
        if ($dia == "") {
            return($mes);
        } else {
            return ("$dia de $mes");
        }
    }

    //Agora basta imprimir na tela...
    return ("$dia de $mes de $ano");

}

/**
 * valor por extenso
 * @param float $valor
 * @param boolean $complemento
 * @return string
 */
function valorPorExtenso($valor, $complemento) {
    $singular = array("centavo", "real", "mil", "milhao", "bilhão", "trilhão", "quatrilhão");
    $plural = array("centavos", "reais", "mil", "milhoes", "bilhões", "trilhões", "quatrilhões");

    $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
    $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
    $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
    $u = array("", "um", "dois", "tres", "quatro", "cinco", "seis", "sete", "oito", "nove");

    $z = 0;
    $rt = '';

    $valor = number_format($valor, 2, ".", ".");
    $inteiro = explode(".", $valor);
    for ($i = 0; $i < count($inteiro); $i++) {
        for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++) {
            $inteiro[$i] = "0" . $inteiro[$i];
        }
    }

    // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;) 
    $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
    for ($i = 0; $i < count($inteiro); $i++) {
        $valor = $inteiro[$i];
        $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
        $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
        $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

        $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
        $t = count($inteiro) - 1 - $i;
        if ($complemento == true) {
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000") {
                $z++;
            } else if ($z > 0) {
                $z--;
            }
            if ($t == 1 && $z > 0 && $inteiro[0] > 0) {
                $r .= (($z > 1) ? " de " : "") . $plural[$t];
            }
        }
        if ($r) {
            $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }
    }

    return($rt ? $rt : "zero");

}

/**
 * getFCColor method helps return a color from arr_FCColors array. It uses
 * cyclic iteration to return a color from a given index. The index value is
 * maintained in FC_ColorCounter
 * @global int $FC_ColorCounter
 * @global array $arr_FCColors
 * @return hexadecimal color (1941A5)
 */
function getFCColor() {
    //accessing the global variables
    //global $FC_ColorCounter;
    //global $arr_FCColors;

    static $FC_ColorCounter = 0;
    $arr_FCColors[0] = "1941A5"; //Dark Blue
    $arr_FCColors[1] = "AFD8F8";
    $arr_FCColors[2] = "F6BD0F";
    $arr_FCColors[3] = "8BBA00";
    $arr_FCColors[4] = "A66EDD";
    $arr_FCColors[5] = "F984A1";
    $arr_FCColors[6] = "CCCC00"; //Chrome Yellow+Green
    $arr_FCColors[7] = "999999"; //Grey
    $arr_FCColors[8] = "0099CC"; //Blue Shade
    $arr_FCColors[9] = "FF0000"; //Bright Red 
    $arr_FCColors[10] = "006F00"; //Dark Green
    $arr_FCColors[11] = "0099FF"; //Blue (Light)
    $arr_FCColors[12] = "FF66CC"; //Dark Pink
    $arr_FCColors[13] = "669966"; //Dirty green
    $arr_FCColors[14] = "7C7CB4"; //Violet shade of blue
    $arr_FCColors[15] = "FF9933"; //Orange
    $arr_FCColors[16] = "9900FF"; //Violet
    $arr_FCColors[17] = "99FFCC"; //Blue+Green Light
    $arr_FCColors[18] = "CCCCFF"; //Light violet
    $arr_FCColors[19] = "669900"; //Shade of green
    //Update index
    $FC_ColorCounter++;
    //Return color
    return($arr_FCColors[$FC_ColorCounter % count($arr_FCColors)]);

}

/**
 * Remove marcara do CPF e do CNPJ
 * @param string $valor (383.884.936-12) (78.099.777/0001-42)
 * @return string (38388493612) (78099777000142)
 */
function cpf_cnpj_para_numero($valor) {
    return str_replace('.', '', str_replace('/', '', str_replace('-', '', $valor)));
}

/**
 * Adiciona mascara ao CEP
 * @param string $cep (85884000)
 * @return string (85.884-000)
 */
function numero_para_cep($cep) {
    $cep = substr($cep, 0, 2) . '.' . substr($cep, 2, 3) . '-' . substr($cep, 5, 3);
    return $cep;

}

/**
 * Verifica se o valor imformado é CPF ou CNPJ e adiciona a respectiva mascara ao mesmo
 * @param string $valor
 * @return type
 */
function numero_para_cnpj_ou_cpf($valor) {
    if (strlen($valor) == 11) {
        return substr($valor, 0, 3) . '.' . substr($valor, 3, 3) . '.' . substr($valor, 6, 3) . '-' . substr($valor, 9, 2);
    } else if (strlen($valor) == 8) {
        return substr($valor, 0, 2) . '.' . substr($valor, 2, 3) . '.' . substr($valor, 5, 3);
    } else {
        return substr($valor, 0, 2) . '.' . substr($valor, 2, 3) . '.' . substr($valor, 5, 3)
                . '/' . substr($valor, 8, 4) . '-' . substr($valor, 12, 2);
    }

}

/**
 * Converte mysql datetime para PT-BR
 * @param string $valor (2015-04-29 12:07:45)
 * @return string   (29/04/2015 - 12:07:45
 */
function datetime_para_exibicao($valor) {
    $valor = substr($valor, 8, 2) . "/" . substr($valor, 5, 2) . "/" . substr($valor, 0, 4) . " - " . substr($valor, 11, 2) . ":" . substr($valor, 14, 2) . ":" . substr($valor, 17, 2);
    return $valor;

}

/**
 * Converte datetime do banco de dados para exibir em componente HTML
 * @param type $val
 * @return type
 */
function db_to_html_datetime($val) {
    return str_replace(" ", "T", $val);

}

/**
 * Converte datetime do HTML para salvar no banco de dados
 * @param type $val
 * @return type
 */
function html_datetime_to_db($val) {
    return str_replace("T", " ", $val);

}

/**
 * Retorna o ultimo dia do mes informado nos parâmetros
 * @param int $mes
 * @param int $ano
 * @return int
 */
function ultimo_dia_mes($mes, $ano) {
    return date("t", mktime(0, 0, 0, $mes, '01', $ano));

}

/**
 * Converte date do banco de dados para exibir em componente HTML
 * @param type $val
 * @return type
 */
function db_to_html_date($val) {
    $val = str_replace('-', '', $val);
    if (!empty($val)) {
        $val = substr($val, 0, 4) . "-" . substr($val, 4, 2) . "-" . substr($val, 6, 2);
    }
    return $val;

}

/**
 * Converte date do HTML para salvar no banco de dados
 * @param type $val
 * @return type
 */
function html_date_to_db($val) {
    return str_replace("-", "", $val);

}

/**
 * Remove a mascara de dinheiro do valor informado
 * @param string $val
 * @return string
 */
function maskmoney_to_db($val) {
    $val = str_replace(".", "", $val);
    $val = str_replace(",", ".", $val);
    $val = str_replace("R$", "", $val);
    $val = str_replace(" ", "", $val);
    return($val);

}

/**
 * Adiciona o numero de dias informados da data informada no parâmetro
 * @param date $date
 * @param int $days
 * @return date
 */
function addDayIntoDate($date, $days) {
    $thisyear = substr($date, 0, 4);
    $thismonth = substr($date, 4, 2);
    $thisday = substr($date, 6, 2);
    $nextdate = mktime(0, 0, 0, $thismonth, $thisday + $days, $thisyear);
    return strftime("%Y%m%d", $nextdate);
}

/**
 * Adiciona o numero de dias informados da data informada no parâmetro
 * @param date $date
 * @param int $days
 * @return date
 */
function addDaysIntoDate($date, $daysToAdd) { 
    $year = substr($date, 0, 4);
    $month = substr($date, 4, 2);
    $day = substr($date, 6, 2); 
    $newDate = $year . '-' . $month . '-' . $day; 
    $newDate = new DateTime($newDate);
    $newDate->add(new DateInterval('P' . $daysToAdd . 'D'));

    return $newDate->format('Y-m-d');
}



/**
 * Remove o numero de dias informados da data informada no parâmetro
 * @param date $date
 * @param int $days
 * @return date
 */
function subDayIntoDate($date, $days) {
    $thisyear = substr($date, 0, 4);
    $thismonth = substr($date, 4, 2);
    $thisday = substr($date, 6, 2);
    $nextdate = mktime(0, 0, 0, $thismonth, $thisday - $days, $thisyear);
    return strftime("%Y%m%d", $nextdate);

}

/**
 * Verifica se o Navegador em uso é igual ao informado no parâmetro
 * @param string $user_agent navegador desejado
 * @return boolean
 */
function browser($user_agent) {

    //$browser = $_SERVER['HTTP_USER_AGENT'];
    $browser = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');

    $browsers = array(
        'ie' => 'MSIE',
        'chrome' => 'Chrome',
        'safari' => 'Safari',
        'opera' => 'Opera',
        'firefox' => 'Firefox'
    );

    return ( strpos($browser, $browsers[$user_agent]) !== false );

}

/**
 * Converte tempo em segundos para texto
 * @param int $secs
 * @return string
 */
function time_elapsed($secs) {
    $bit = array(
        'y' => $secs / 31556926 % 12,
        'w' => $secs / 604800 % 52,
        'd' => $secs / 86400 % 7,
        'h' => $secs / 3600 % 24,
        'm' => $secs / 60 % 60,
        's' => $secs % 60
    );
    $ret[0] = '';
    foreach ($bit as $k => $v) {
        if ($v > 0) {
            $ret[] = $v . $k;
        }
    }
    $a = join(' ', $ret);
    if ($a != '') {
        return $a;
    } else {
        return '0s';
    }

}

/**
 * Converte tempo em segundos para texto
 * @param int $time O tempo em segundos
 * @return string O tempo em forma textual
 */
function time2text($time) {
    $response = array();
    $years = floor($time / (86400 * 365));
    $time = $time % (86400 * 365);
    $months = floor($time / (86400 * 30));
    $time = $time % (86400 * 30);
    $days = floor($time / 86400);
    $time = $time % 86400;
    $hours = floor($time / (3600));
    $time = $time % 3600;
    $minutes = floor($time / 60);
    $seconds = $time % 60;
    if ($years > 0) {
        $response[] = $years . ' ano' . ($years > 1 ? 's' : ' ');
    }
    if ($months > 0) {
        $response[] = $months . ' mes' . ($months > 1 ? 'es' : ' ');
    }
    if ($days > 0) {
        $response[] = $days . ' dia' . ($days > 1 ? 's' : ' ');
    }
    if ($hours > 0) {
        $response[] = $hours . ' hora' . ($hours > 1 ? 's' : ' ');
    }
    if ($minutes > 0) {
        $response[] = $minutes . ' minuto' . ($minutes > 1 ? 's' : ' ');
    }
    if ($seconds > 0) {
        $response[] = $seconds . ' segundo' . ($seconds > 1 ? 's' : ' ');
    }
    return implode(', ', $response);

}

/**
 * Retorna um option de acordo com os parâmetros informados
 * @param string $id
 * @param string $descricao
 * @param string $default
 * @param string $extras
 * @return string
 */
function add_option_html_select($id, $descricao, $default = "§", $extras = "", $quebra = true) {
    if (is_array($default)) {
        $select = in_array($id, $default) ? "selected" : "";
    } else {
        $select = $id == $default ? "selected" : "";
    }

    if ($quebra) {
        return "<option value='$id' $extras $select>$descricao</option>" . PHP_EOL;
    } else {
        return "<option value='$id' $extras $select>$descricao</option>";
    }

}

/**
 * Remove os caracteres especiais de uma string
 * @param string $string (Ação)
 * @return string (Acao)
 */
function rce(string $string) {
    return (string) preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç)/", "/(Ç)/", '/[\/\\\\]/'), explode(" ", "a A e E i I o O u U n N c C _"), $string);

}

function semanas_ref_now($ref) {

    $sem_ini = strftime('%U', strtotime($ref . "01"));
    $last_day = date("Ymt", strtotime($ref . "01"));
    $last_week = strftime('%U', strtotime($last_day));
    $hoje = date("Ymd");
    $sem_atual = strftime('%U', strtotime($hoje));
    $sems = 0;
    if ($ref <> date("Ym")) {
        $sems = $last_week - $sem_ini + 1;
    } else {
        $sems = $sem_atual - $sem_ini + 1;
    }
    return $sems;

}

/**
 * Redirect with POST data.
 *
 * @param string $url URL.
 * @param array $params parêmetros POST. Exemplo: array('foo' => 'var', 'id' => 123)
 */
function redirect_post($url, array $params) {
    $return = "<html>" . PHP_EOL
            . "    <head>" . PHP_EOL
            . "    </head>" . PHP_EOL
            . "    <body>    " . PHP_EOL
            . "        <script>" . PHP_EOL
            . "            var f = document.createElement('form');" . PHP_EOL
            . "            f.action = '{$url}';" . PHP_EOL
            . "            f.method = 'POST';" . PHP_EOL
            . "            f.target = '';" . PHP_EOL;

    foreach ($params as $key => $value) {
        $return .= "            var i = document.createElement('input');" . PHP_EOL
                . "            i.type = 'hidden';" . PHP_EOL
                . "            i.name = '{$key}';" . PHP_EOL
                . "            i.value = '{$value}';" . PHP_EOL
                . "            f.appendChild(i);" . PHP_EOL;
    }
    $return .= "            document.body.appendChild(f);" . PHP_EOL
            . "            f.submit();" . PHP_EOL
            . "        </script>" . PHP_EOL
            . "    </body>" . PHP_EOL
            . "</html>";
    return $return;

}

/**
 * Função para verificar se o CNPJ informado é valido
 * @param type $cnpj
 * @return boolean
 */
function valida_cnpj($cnpj) {
    $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
    // Valida tamanho
    if (strlen($cnpj) != 14)
        return false;
    // Valida primeiro dígito verificador
    for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
        $soma += $cnpj[$i] * $j;
        $j = ($j == 2) ? 9 : $j - 1;
    }
    $resto = $soma % 11;
    if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
        return false;
    // Valida segundo dígito verificador
    for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
        $soma += $cnpj[$i] * $j;
        $j = ($j == 2) ? 9 : $j - 1;
    }
    $resto = $soma % 11;
    return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);

}

/**
 * Função que verifica se a data informada é dia util.
 * Caso não seja útil retorna o o dia útil de acordo com a operação.
 * 
 * + proximo
 * - anterior
 * 
 * @param string $data ("YYYYMMDD")
 * @param string $operacao ("+","-")
 */
function so_dia_util(string $data, string $operacao = "+") {
    $time = strtotime(db_to_html_date($data));
    $d_semana = date("w", $time);
    if ($d_semana == 6 || $d_semana == 0) {
        $data = so_dia_util(date("Ymd", strtotime("{$operacao}1 day", $time)), $operacao);
    }
    return $data;

}

/**
 * Função que ajusta a mascara do telefone conforme o valor repassado.
 * 
 * 
 * @param string $TEL (Número do telefone)
 */
function masc_tel($TEL) {
    $tam = strlen(preg_replace("/[^0-9]/", "", $TEL));
      if ($tam == 13) { // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS e 9 dígitos
      return "+".substr($TEL,0,$tam-11)."(".substr($TEL,$tam-11,2).")".substr($TEL,$tam-9,5)."-".substr($TEL,-4);
      }
      if ($tam == 12) { // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS
      return "+".substr($TEL,0,$tam-10)."(".substr($TEL,$tam-10,2).")".substr($TEL,$tam-8,4)."-".substr($TEL,-4);
      }
      if ($tam == 11) { // COM CÓDIGO DE ÁREA NACIONAL e 9 dígitos
      return "(".substr($TEL,0,2).")".substr($TEL,2,5)."-".substr($TEL,7,11);
      }
      if ($tam == 10) { // COM CÓDIGO DE ÁREA NACIONAL
      return "(".substr($TEL,0,2).")".substr($TEL,2,4)."-".substr($TEL,6,10);
      }
      if ($tam <= 9) { // SEM CÓDIGO DE ÁREA
      return substr($TEL,0,$tam-4)."-".substr($TEL,-4);
      }
  }

 /**
 * Função que retorna o intervalo de horas, minutos e segundos entre duas variaveis HH:mm:ss.
 * 
 * 
 * @param string $hora_ini (Hora Inícial)
 * @param string $hora_fim (Hora Final)
 */
 function dif_time($hora_ini, $hora_fim){
	$inicio = strtotime($hora_ini);
	$fim = strtotime($hora_fim);
	$diferenca = $fim - $inicio;
	$hora = floor($diferenca / 3600);
	$minutos = floor(($diferenca / 60) % 60);
	$segundos = floor($diferenca % 60);
	$resultado = "{$hora}:{$minutos}:{$segundos}";
return $resultado;
}

/**
 * Função que retorna o intervalo de dias entre duas datas YYYYMMDD.
 * 
 * 
 * @param string $dt1 (Data Inícial)
 * @param string $dt2 (Data Final)
 */
function dif_date($dt1, $dt2){
	$data_inicio = new DateTime($dt1);
    $data_fim = new DateTime($dt2);

    // Resgata diferença entre as datas
    $dateInterval = $data_inicio->diff($data_fim);
    return $dateInterval->days;
}

/**
 * Função que retorna o valor centesimal de uma hora.
 * 
 * 
 * @param numerico $hora_normal (Hora Normal 10.18)
 */
 function hora_para_centesimal($hora_normal){
	$hora_centesimal =(int)$hora_normal + (round((($hora_normal-(int)$hora_normal)/60)*100,2));
return $hora_centesimal;
}

/**
 * Função que retorna o valor normal de uma hora sobre uma hora centesimal.
 * 
 * 
 * @param numerico $hora_centesimal (Hora Centesimal 10.30)
 */
 function centesimal_para_hora($hora_centesimal){
	$hora_normal = (int)$hora_centesimal + (round((($hora_centesimal-(int)$hora_centesimal)*60)/100,2));
return $hora_normal;
}

/**
 * Função que retorna True ou False caso o ip e porta responda ao ping.
 * 
 * 
 * @param numerico $ip (172.16.5.1)
 * @param numerico $porta (135)
 * @param numerico $tempo (5 segundos para timeout do ping)
 */
function pinga($ip, $porta, $tempo){
$conectado = @ fsockopen($ip, $porta, $numeroDoErro, $stringDoErro, $tempo); 
  if ($conectado) {
    $ret = True;
  } else {
    $ret = False;
  }
  return $ret;
}

/**
 * Função que retorna True ou False caso o e-mail seja valido ou não.
 * 
 * 
 * @param numerico $email ('meuemail@dominio.com.br')
 */
function validaEmail($email) {
    if(filter_var($email, FILTER_VALIDATE_EMAIL))
        return true;
    else
        return false;
}

/**
 * 
 */
function dataSort($date)
{
    //entrada DD/MM/YYYY
    //saída YYYYMMDD
    $dia = substr($date, 0, 2);
    $mes = substr($date, 3, 2);
    $ano = substr($date, 6, 4);
    $formatedDate = $ano . $mes . $dia;
    return $formatedDate;
}

/**
 * like
 * Function that emulates LIKE used in relational databases in 
 * order to look for values in a string.
 * 
 * follow example of use:
 * var_dump(like('rod%', 'rodrigorigotti'));   // bool(true)
 * var_dump(like('%tti', 'rodrigorigotti'));   // bool(true)
 * var_dump(like('%gori%', 'rodrigorigotti')); // bool(true)
 * var_dump(like('%lala', 'rodrigorigotti'));  // bool(false)
 * var_dump(like('lala%', 'rodrigorigotti'));  // bool(false)
 * var_dump(like('%lala%', 'rodrigorigotti')); // bool(false)
 * 
 * @param string $needle 
 * @param string $haystack
 * @return bool
 */
function like($needle, $haystack)
{
    $regex = '/' . str_replace('%', '.*?', $needle) . '/';
    return preg_match($regex, $haystack) > 0;
}

