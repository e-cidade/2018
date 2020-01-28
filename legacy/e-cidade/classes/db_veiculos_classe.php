<?PHP
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

//MODULO: veiculos
//CLASSE DA ENTIDADE veiculos
class cl_veiculos {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $ve01_codigo = 0;
   var $ve01_placa = null;
   var $ve01_veiccadtipo = 0;
   var $ve01_veiccadmarca = 0;
   var $ve01_veiccadmodelo = 0;
   var $ve01_veiccadcor = 0;
   var $ve01_veiccadproced = 0;
   var $ve01_veiccadcateg = 0;
   var $ve01_chassi = null;
   var $ve01_ranavam = 0;
   var $ve01_placanum = 0;
   var $ve01_certif = null;
   var $ve01_quantpotencia = 0;
   var $ve01_veiccadpotencia = 0;
   var $ve01_quantcapacidad = 0;
   var $ve01_veiccadtipocapacidade = 0;
   var $ve01_dtaquis_dia = null;
   var $ve01_dtaquis_mes = null;
   var $ve01_dtaquis_ano = null;
   var $ve01_dtaquis = null;
   var $ve01_veiccadcategcnh = 0;
   var $ve01_anofab = 0;
   var $ve01_anomod = 0;
   var $ve01_ceplocalidades = 0;
   var $ve01_ativo = 0;
   var $ve01_veictipoabast = 0;
   var $ve01_medidaini = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ve01_codigo = int4 = Código do Veiculo
                 ve01_placa = varchar(7) = Placa
                 ve01_veiccadtipo = int4 = Tipo
                 ve01_veiccadmarca = int4 = Marca
                 ve01_veiccadmodelo = int4 = Modelo
                 ve01_veiccadcor = int4 = Cor
                 ve01_veiccadproced = int4 = Procedência
                 ve01_veiccadcateg = int4 = Categoria
                 ve01_chassi = varchar(30) = Nº do Chassi
                 ve01_ranavam = int8 = Renavam
                 ve01_placanum = int8 = Placa em Número
                 ve01_certif = varchar(20) = Nº do Certificado
                 ve01_quantpotencia = int4 = Quant. Potência
                 ve01_veiccadpotencia = int4 = Potência
                 ve01_quantcapacidad = int4 = Quant. Capacidade
                 ve01_veiccadtipocapacidade = int4 = Tipo de Capacidade
                 ve01_dtaquis = date = Data de Aquisição
                 ve01_veiccadcategcnh = int4 = Categoria CNH Exigida
                 ve01_anofab = int4 = Ano de Fabricação
                 ve01_anomod = int4 = Ano do Modelo
                 ve01_ceplocalidades = int8 = Municipio
                 ve01_ativo = int4 = Ativo
                 ve01_veictipoabast = int4 = Tipo de Abastecimento
                 ve01_medidaini = float8 = Medida Inicial
                 ";
   //funcao construtor da classe
   function cl_veiculos() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veiculos");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->ve01_codigo = ($this->ve01_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_codigo"]:$this->ve01_codigo);
       $this->ve01_placa = ($this->ve01_placa == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_placa"]:$this->ve01_placa);
       $this->ve01_veiccadtipo = ($this->ve01_veiccadtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_veiccadtipo"]:$this->ve01_veiccadtipo);
       $this->ve01_veiccadmarca = ($this->ve01_veiccadmarca == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_veiccadmarca"]:$this->ve01_veiccadmarca);
       $this->ve01_veiccadmodelo = ($this->ve01_veiccadmodelo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_veiccadmodelo"]:$this->ve01_veiccadmodelo);
       $this->ve01_veiccadcor = ($this->ve01_veiccadcor == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_veiccadcor"]:$this->ve01_veiccadcor);
       $this->ve01_veiccadproced = ($this->ve01_veiccadproced == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_veiccadproced"]:$this->ve01_veiccadproced);
       $this->ve01_veiccadcateg = ($this->ve01_veiccadcateg == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_veiccadcateg"]:$this->ve01_veiccadcateg);
       $this->ve01_chassi = ($this->ve01_chassi == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_chassi"]:$this->ve01_chassi);
       $this->ve01_ranavam = ($this->ve01_ranavam == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_ranavam"]:$this->ve01_ranavam);
       $this->ve01_placanum = ($this->ve01_placanum == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_placanum"]:$this->ve01_placanum);
       $this->ve01_certif = ($this->ve01_certif == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_certif"]:$this->ve01_certif);
       $this->ve01_quantpotencia = ($this->ve01_quantpotencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_quantpotencia"]:$this->ve01_quantpotencia);
       $this->ve01_veiccadpotencia = ($this->ve01_veiccadpotencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_veiccadpotencia"]:$this->ve01_veiccadpotencia);
       $this->ve01_quantcapacidad = ($this->ve01_quantcapacidad == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_quantcapacidad"]:$this->ve01_quantcapacidad);
       $this->ve01_veiccadtipocapacidade = ($this->ve01_veiccadtipocapacidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_veiccadtipocapacidade"]:$this->ve01_veiccadtipocapacidade);
       if($this->ve01_dtaquis == ""){
         $this->ve01_dtaquis_dia = ($this->ve01_dtaquis_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_dtaquis_dia"]:$this->ve01_dtaquis_dia);
         $this->ve01_dtaquis_mes = ($this->ve01_dtaquis_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_dtaquis_mes"]:$this->ve01_dtaquis_mes);
         $this->ve01_dtaquis_ano = ($this->ve01_dtaquis_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_dtaquis_ano"]:$this->ve01_dtaquis_ano);
         if($this->ve01_dtaquis_dia != ""){
            $this->ve01_dtaquis = $this->ve01_dtaquis_ano."-".$this->ve01_dtaquis_mes."-".$this->ve01_dtaquis_dia;
         }
       }
       $this->ve01_veiccadcategcnh = ($this->ve01_veiccadcategcnh == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_veiccadcategcnh"]:$this->ve01_veiccadcategcnh);
       $this->ve01_anofab = ($this->ve01_anofab == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_anofab"]:$this->ve01_anofab);
       $this->ve01_anomod = ($this->ve01_anomod == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_anomod"]:$this->ve01_anomod);
       $this->ve01_ceplocalidades = ($this->ve01_ceplocalidades == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_ceplocalidades"]:$this->ve01_ceplocalidades);
       $this->ve01_ativo = ($this->ve01_ativo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_ativo"]:$this->ve01_ativo);
       $this->ve01_veictipoabast = ($this->ve01_veictipoabast == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_veictipoabast"]:$this->ve01_veictipoabast);
       $this->ve01_medidaini = ($this->ve01_medidaini == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_medidaini"]:$this->ve01_medidaini);
     }else{
       $this->ve01_codigo = ($this->ve01_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve01_codigo"]:$this->ve01_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ve01_codigo){
      $this->atualizacampos();
     if($this->ve01_placa == null ){
       $this->erro_sql = " Campo Placa nao Informado.";
       $this->erro_campo = "ve01_placa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_veiccadtipo == null ){
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "ve01_veiccadtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_veiccadmarca == null ){
       $this->erro_sql = " Campo Marca nao Informado.";
       $this->erro_campo = "ve01_veiccadmarca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_veiccadmodelo == null ){
       $this->erro_sql = " Campo Modelo nao Informado.";
       $this->erro_campo = "ve01_veiccadmodelo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_veiccadcor == null ){
       $this->erro_sql = " Campo Cor nao Informado.";
       $this->erro_campo = "ve01_veiccadcor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_veiccadproced == null ){
       $this->erro_sql = " Campo Procedência nao Informado.";
       $this->erro_campo = "ve01_veiccadproced";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_veiccadcateg == null ){
       $this->erro_sql = " Campo Categoria nao Informado.";
       $this->erro_campo = "ve01_veiccadcateg";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_chassi == null ){
       $this->erro_sql = " Campo Nº do Chassi nao Informado.";
       $this->erro_campo = "ve01_chassi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_ranavam == null ){
       $this->erro_sql = " Campo Renavam nao Informado.";
       $this->erro_campo = "ve01_ranavam";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_placanum == null ){
       $this->erro_sql = " Campo Placa em Número nao Informado.";
       $this->erro_campo = "ve01_placanum";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_certif == null ){
       $this->erro_sql = " Campo Nº do Certificado nao Informado.";
       $this->erro_campo = "ve01_certif";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_quantpotencia == null ){
       $this->erro_sql = " Campo Quant. Potência nao Informado.";
       $this->erro_campo = "ve01_quantpotencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_veiccadpotencia == null ){
       $this->erro_sql = " Campo Potência nao Informado.";
       $this->erro_campo = "ve01_veiccadpotencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_quantcapacidad == null ){
       $this->erro_sql = " Campo Quant. Capacidade nao Informado.";
       $this->erro_campo = "ve01_quantcapacidad";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_veiccadtipocapacidade == null ){
       $this->erro_sql = " Campo Tipo de Capacidade nao Informado.";
       $this->erro_campo = "ve01_veiccadtipocapacidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_dtaquis == null ){
       $this->erro_sql = " Campo Data de Aquisição nao Informado.";
       $this->erro_campo = "ve01_dtaquis_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_veiccadcategcnh == null ){
       $this->erro_sql = " Campo Categoria CNH Exigida nao Informado.";
       $this->erro_campo = "ve01_veiccadcategcnh";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_anofab == null ){
       $this->erro_sql = " Campo Ano de Fabricação nao Informado.";
       $this->erro_campo = "ve01_anofab";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_anomod == null ){
       $this->erro_sql = " Campo Ano do Modelo nao Informado.";
       $this->erro_campo = "ve01_anomod";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_ceplocalidades == null ){
       $this->erro_sql = " Campo Municipio nao Informado.";
       $this->erro_campo = "ve01_ceplocalidades";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_ativo == null ){
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "ve01_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_veictipoabast == null ){
       $this->erro_sql = " Campo Tipo de Abastecimento nao Informado.";
       $this->erro_campo = "ve01_veictipoabast";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve01_medidaini == null ){
       $this->erro_sql = " Campo Medida Inicial nao Informado.";
       $this->erro_campo = "ve01_medidaini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve01_codigo == "" || $ve01_codigo == null ){
       $result = db_query("select nextval('veiculos_ve01_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veiculos_ve01_codigo_seq do campo: ve01_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ve01_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from veiculos_ve01_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve01_codigo)){
         $this->erro_sql = " Campo ve01_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve01_codigo = $ve01_codigo;
       }
     }
     if(($this->ve01_codigo == null) || ($this->ve01_codigo == "") ){
       $this->erro_sql = " Campo ve01_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veiculos(
                                       ve01_codigo
                                      ,ve01_placa
                                      ,ve01_veiccadtipo
                                      ,ve01_veiccadmarca
                                      ,ve01_veiccadmodelo
                                      ,ve01_veiccadcor
                                      ,ve01_veiccadproced
                                      ,ve01_veiccadcateg
                                      ,ve01_chassi
                                      ,ve01_ranavam
                                      ,ve01_placanum
                                      ,ve01_certif
                                      ,ve01_quantpotencia
                                      ,ve01_veiccadpotencia
                                      ,ve01_quantcapacidad
                                      ,ve01_veiccadtipocapacidade
                                      ,ve01_dtaquis
                                      ,ve01_veiccadcategcnh
                                      ,ve01_anofab
                                      ,ve01_anomod
                                      ,ve01_ceplocalidades
                                      ,ve01_ativo
                                      ,ve01_veictipoabast
                                      ,ve01_medidaini
                       )
                values (
                                $this->ve01_codigo
                               ,'$this->ve01_placa'
                               ,$this->ve01_veiccadtipo
                               ,$this->ve01_veiccadmarca
                               ,$this->ve01_veiccadmodelo
                               ,$this->ve01_veiccadcor
                               ,$this->ve01_veiccadproced
                               ,$this->ve01_veiccadcateg
                               ,'$this->ve01_chassi'
                               ,$this->ve01_ranavam
                               ,$this->ve01_placanum
                               ,'$this->ve01_certif'
                               ,$this->ve01_quantpotencia
                               ,$this->ve01_veiccadpotencia
                               ,$this->ve01_quantcapacidad
                               ,$this->ve01_veiccadtipocapacidade
                               ,".($this->ve01_dtaquis == "null" || $this->ve01_dtaquis == ""?"null":"'".$this->ve01_dtaquis."'")."
                               ,$this->ve01_veiccadcategcnh
                               ,$this->ve01_anofab
                               ,$this->ve01_anomod
                               ,$this->ve01_ceplocalidades
                               ,$this->ve01_ativo
                               ,$this->ve01_veictipoabast
                               ,$this->ve01_medidaini
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Veiculos ($this->ve01_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Veiculos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Veiculos ($this->ve01_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve01_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve01_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9248,'$this->ve01_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1590,9248,'','".AddSlashes(pg_result($resaco,0,'ve01_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9249,'','".AddSlashes(pg_result($resaco,0,'ve01_placa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9250,'','".AddSlashes(pg_result($resaco,0,'ve01_veiccadtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9251,'','".AddSlashes(pg_result($resaco,0,'ve01_veiccadmarca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9252,'','".AddSlashes(pg_result($resaco,0,'ve01_veiccadmodelo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9253,'','".AddSlashes(pg_result($resaco,0,'ve01_veiccadcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9309,'','".AddSlashes(pg_result($resaco,0,'ve01_veiccadproced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9310,'','".AddSlashes(pg_result($resaco,0,'ve01_veiccadcateg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9254,'','".AddSlashes(pg_result($resaco,0,'ve01_chassi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9311,'','".AddSlashes(pg_result($resaco,0,'ve01_ranavam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9312,'','".AddSlashes(pg_result($resaco,0,'ve01_placanum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9255,'','".AddSlashes(pg_result($resaco,0,'ve01_certif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9313,'','".AddSlashes(pg_result($resaco,0,'ve01_quantpotencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9314,'','".AddSlashes(pg_result($resaco,0,'ve01_veiccadpotencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9315,'','".AddSlashes(pg_result($resaco,0,'ve01_quantcapacidad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9316,'','".AddSlashes(pg_result($resaco,0,'ve01_veiccadtipocapacidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9258,'','".AddSlashes(pg_result($resaco,0,'ve01_dtaquis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9260,'','".AddSlashes(pg_result($resaco,0,'ve01_veiccadcategcnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9261,'','".AddSlashes(pg_result($resaco,0,'ve01_anofab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9317,'','".AddSlashes(pg_result($resaco,0,'ve01_anomod'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9318,'','".AddSlashes(pg_result($resaco,0,'ve01_ceplocalidades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,9262,'','".AddSlashes(pg_result($resaco,0,'ve01_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,11076,'','".AddSlashes(pg_result($resaco,0,'ve01_veictipoabast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1590,11080,'','".AddSlashes(pg_result($resaco,0,'ve01_medidaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ve01_codigo=null) {
      $this->atualizacampos();
     $sql = " update veiculos set ";
     $virgula = "";
     if(trim($this->ve01_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_codigo"])){
       $sql  .= $virgula." ve01_codigo = $this->ve01_codigo ";
       $virgula = ",";
       if(trim($this->ve01_codigo) == null ){
         $this->erro_sql = " Campo Código do Veiculo nao Informado.";
         $this->erro_campo = "ve01_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_placa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_placa"])){
       $sql  .= $virgula." ve01_placa = '$this->ve01_placa' ";
       $virgula = ",";
       if(trim($this->ve01_placa) == null ){
         $this->erro_sql = " Campo Placa nao Informado.";
         $this->erro_campo = "ve01_placa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_veiccadtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadtipo"])){
       $sql  .= $virgula." ve01_veiccadtipo = $this->ve01_veiccadtipo ";
       $virgula = ",";
       if(trim($this->ve01_veiccadtipo) == null ){
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "ve01_veiccadtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_veiccadmarca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadmarca"])){
       $sql  .= $virgula." ve01_veiccadmarca = $this->ve01_veiccadmarca ";
       $virgula = ",";
       if(trim($this->ve01_veiccadmarca) == null ){
         $this->erro_sql = " Campo Marca nao Informado.";
         $this->erro_campo = "ve01_veiccadmarca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_veiccadmodelo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadmodelo"])){
       $sql  .= $virgula." ve01_veiccadmodelo = $this->ve01_veiccadmodelo ";
       $virgula = ",";
       if(trim($this->ve01_veiccadmodelo) == null ){
         $this->erro_sql = " Campo Modelo nao Informado.";
         $this->erro_campo = "ve01_veiccadmodelo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_veiccadcor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadcor"])){
       $sql  .= $virgula." ve01_veiccadcor = $this->ve01_veiccadcor ";
       $virgula = ",";
       if(trim($this->ve01_veiccadcor) == null ){
         $this->erro_sql = " Campo Cor nao Informado.";
         $this->erro_campo = "ve01_veiccadcor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_veiccadproced)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadproced"])){
       $sql  .= $virgula." ve01_veiccadproced = $this->ve01_veiccadproced ";
       $virgula = ",";
       if(trim($this->ve01_veiccadproced) == null ){
         $this->erro_sql = " Campo Procedência nao Informado.";
         $this->erro_campo = "ve01_veiccadproced";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_veiccadcateg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadcateg"])){
       $sql  .= $virgula." ve01_veiccadcateg = $this->ve01_veiccadcateg ";
       $virgula = ",";
       if(trim($this->ve01_veiccadcateg) == null ){
         $this->erro_sql = " Campo Categoria nao Informado.";
         $this->erro_campo = "ve01_veiccadcateg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_chassi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_chassi"])){
       $sql  .= $virgula." ve01_chassi = '$this->ve01_chassi' ";
       $virgula = ",";
       if(trim($this->ve01_chassi) == null ){
         $this->erro_sql = " Campo Nº do Chassi nao Informado.";
         $this->erro_campo = "ve01_chassi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_ranavam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_ranavam"])){
       $sql  .= $virgula." ve01_ranavam = $this->ve01_ranavam ";
       $virgula = ",";
       if(trim($this->ve01_ranavam) == null ){
         $this->erro_sql = " Campo Renavam nao Informado.";
         $this->erro_campo = "ve01_ranavam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_placanum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_placanum"])){
       $sql  .= $virgula." ve01_placanum = $this->ve01_placanum ";
       $virgula = ",";
       if(trim($this->ve01_placanum) == null ){
         $this->erro_sql = " Campo Placa em Número nao Informado.";
         $this->erro_campo = "ve01_placanum";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_certif)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_certif"])){
       $sql  .= $virgula." ve01_certif = '$this->ve01_certif' ";
       $virgula = ",";
       if(trim($this->ve01_certif) == null ){
         $this->erro_sql = " Campo Nº do Certificado nao Informado.";
         $this->erro_campo = "ve01_certif";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_quantpotencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_quantpotencia"])){
       $sql  .= $virgula." ve01_quantpotencia = $this->ve01_quantpotencia ";
       $virgula = ",";
       if(trim($this->ve01_quantpotencia) == null ){
         $this->erro_sql = " Campo Quant. Potência nao Informado.";
         $this->erro_campo = "ve01_quantpotencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_veiccadpotencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadpotencia"])){
       $sql  .= $virgula." ve01_veiccadpotencia = $this->ve01_veiccadpotencia ";
       $virgula = ",";
       if(trim($this->ve01_veiccadpotencia) == null ){
         $this->erro_sql = " Campo Potência nao Informado.";
         $this->erro_campo = "ve01_veiccadpotencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_quantcapacidad)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_quantcapacidad"])){
       $sql  .= $virgula." ve01_quantcapacidad = $this->ve01_quantcapacidad ";
       $virgula = ",";
       if(trim($this->ve01_quantcapacidad) == null ){
         $this->erro_sql = " Campo Quant. Capacidade nao Informado.";
         $this->erro_campo = "ve01_quantcapacidad";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_veiccadtipocapacidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadtipocapacidade"])){
       $sql  .= $virgula." ve01_veiccadtipocapacidade = $this->ve01_veiccadtipocapacidade ";
       $virgula = ",";
       if(trim($this->ve01_veiccadtipocapacidade) == null ){
         $this->erro_sql = " Campo Tipo de Capacidade nao Informado.";
         $this->erro_campo = "ve01_veiccadtipocapacidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_dtaquis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_dtaquis_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve01_dtaquis_dia"] !="") ){
       $sql  .= $virgula." ve01_dtaquis = '$this->ve01_dtaquis' ";
       $virgula = ",";
       if(trim($this->ve01_dtaquis) == null ){
         $this->erro_sql = " Campo Data de Aquisição nao Informado.";
         $this->erro_campo = "ve01_dtaquis_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_dtaquis_dia"])){
         $sql  .= $virgula." ve01_dtaquis = null ";
         $virgula = ",";
         if(trim($this->ve01_dtaquis) == null ){
           $this->erro_sql = " Campo Data de Aquisição nao Informado.";
           $this->erro_campo = "ve01_dtaquis_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve01_veiccadcategcnh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadcategcnh"])){
       $sql  .= $virgula." ve01_veiccadcategcnh = $this->ve01_veiccadcategcnh ";
       $virgula = ",";
       if(trim($this->ve01_veiccadcategcnh) == null ){
         $this->erro_sql = " Campo Categoria CNH Exigida nao Informado.";
         $this->erro_campo = "ve01_veiccadcategcnh";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_anofab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_anofab"])){
       $sql  .= $virgula." ve01_anofab = $this->ve01_anofab ";
       $virgula = ",";
       if(trim($this->ve01_anofab) == null ){
         $this->erro_sql = " Campo Ano de Fabricação nao Informado.";
         $this->erro_campo = "ve01_anofab";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_anomod)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_anomod"])){
       $sql  .= $virgula." ve01_anomod = $this->ve01_anomod ";
       $virgula = ",";
       if(trim($this->ve01_anomod) == null ){
         $this->erro_sql = " Campo Ano do Modelo nao Informado.";
         $this->erro_campo = "ve01_anomod";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_ceplocalidades)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_ceplocalidades"])){
       $sql  .= $virgula." ve01_ceplocalidades = $this->ve01_ceplocalidades ";
       $virgula = ",";
       if(trim($this->ve01_ceplocalidades) == null ){
         $this->erro_sql = " Campo Municipio nao Informado.";
         $this->erro_campo = "ve01_ceplocalidades";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_ativo"])){
       $sql  .= $virgula." ve01_ativo = $this->ve01_ativo ";
       $virgula = ",";
       if(trim($this->ve01_ativo) == null ){
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "ve01_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_veictipoabast)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_veictipoabast"])){
       $sql  .= $virgula." ve01_veictipoabast = $this->ve01_veictipoabast ";
       $virgula = ",";
       if(trim($this->ve01_veictipoabast) == null ){
         $this->erro_sql = " Campo Tipo de Abastecimento nao Informado.";
         $this->erro_campo = "ve01_veictipoabast";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve01_medidaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve01_medidaini"])){
       $sql  .= $virgula." ve01_medidaini = $this->ve01_medidaini ";
       $virgula = ",";
       if(trim($this->ve01_medidaini) == null ){
         $this->erro_sql = " Campo Medida Inicial nao Informado.";
         $this->erro_campo = "ve01_medidaini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ve01_codigo!=null){
       $sql .= " ve01_codigo = $this->ve01_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ve01_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9248,'$this->ve01_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1590,9248,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_codigo'))."','$this->ve01_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_placa"]))
           $resac = db_query("insert into db_acount values($acount,1590,9249,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_placa'))."','$this->ve01_placa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadtipo"]))
           $resac = db_query("insert into db_acount values($acount,1590,9250,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_veiccadtipo'))."','$this->ve01_veiccadtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadmarca"]))
           $resac = db_query("insert into db_acount values($acount,1590,9251,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_veiccadmarca'))."','$this->ve01_veiccadmarca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadmodelo"]))
           $resac = db_query("insert into db_acount values($acount,1590,9252,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_veiccadmodelo'))."','$this->ve01_veiccadmodelo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadcor"]))
           $resac = db_query("insert into db_acount values($acount,1590,9253,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_veiccadcor'))."','$this->ve01_veiccadcor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadproced"]))
           $resac = db_query("insert into db_acount values($acount,1590,9309,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_veiccadproced'))."','$this->ve01_veiccadproced',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadcateg"]))
           $resac = db_query("insert into db_acount values($acount,1590,9310,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_veiccadcateg'))."','$this->ve01_veiccadcateg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_chassi"]))
           $resac = db_query("insert into db_acount values($acount,1590,9254,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_chassi'))."','$this->ve01_chassi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_ranavam"]))
           $resac = db_query("insert into db_acount values($acount,1590,9311,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_ranavam'))."','$this->ve01_ranavam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_placanum"]))
           $resac = db_query("insert into db_acount values($acount,1590,9312,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_placanum'))."','$this->ve01_placanum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_certif"]))
           $resac = db_query("insert into db_acount values($acount,1590,9255,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_certif'))."','$this->ve01_certif',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_quantpotencia"]))
           $resac = db_query("insert into db_acount values($acount,1590,9313,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_quantpotencia'))."','$this->ve01_quantpotencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadpotencia"]))
           $resac = db_query("insert into db_acount values($acount,1590,9314,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_veiccadpotencia'))."','$this->ve01_veiccadpotencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_quantcapacidad"]))
           $resac = db_query("insert into db_acount values($acount,1590,9315,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_quantcapacidad'))."','$this->ve01_quantcapacidad',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadtipocapacidade"]))
           $resac = db_query("insert into db_acount values($acount,1590,9316,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_veiccadtipocapacidade'))."','$this->ve01_veiccadtipocapacidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_dtaquis"]))
           $resac = db_query("insert into db_acount values($acount,1590,9258,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_dtaquis'))."','$this->ve01_dtaquis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_veiccadcategcnh"]))
           $resac = db_query("insert into db_acount values($acount,1590,9260,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_veiccadcategcnh'))."','$this->ve01_veiccadcategcnh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_anofab"]))
           $resac = db_query("insert into db_acount values($acount,1590,9261,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_anofab'))."','$this->ve01_anofab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_anomod"]))
           $resac = db_query("insert into db_acount values($acount,1590,9317,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_anomod'))."','$this->ve01_anomod',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_ceplocalidades"]))
           $resac = db_query("insert into db_acount values($acount,1590,9318,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_ceplocalidades'))."','$this->ve01_ceplocalidades',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_ativo"]))
           $resac = db_query("insert into db_acount values($acount,1590,9262,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_ativo'))."','$this->ve01_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_veictipoabast"]))
           $resac = db_query("insert into db_acount values($acount,1590,11076,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_veictipoabast'))."','$this->ve01_veictipoabast',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve01_medidaini"]))
           $resac = db_query("insert into db_acount values($acount,1590,11080,'".AddSlashes(pg_result($resaco,$conresaco,'ve01_medidaini'))."','$this->ve01_medidaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Veiculos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve01_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Veiculos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve01_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve01_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ve01_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve01_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9248,'$ve01_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1590,9248,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9249,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_placa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9250,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_veiccadtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9251,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_veiccadmarca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9252,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_veiccadmodelo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9253,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_veiccadcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9309,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_veiccadproced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9310,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_veiccadcateg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9254,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_chassi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9311,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_ranavam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9312,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_placanum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9255,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_certif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9313,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_quantpotencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9314,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_veiccadpotencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9315,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_quantcapacidad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9316,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_veiccadtipocapacidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9258,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_dtaquis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9260,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_veiccadcategcnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9261,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_anofab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9317,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_anomod'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9318,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_ceplocalidades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,9262,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,11076,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_veictipoabast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1590,11080,'','".AddSlashes(pg_result($resaco,$iresaco,'ve01_medidaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veiculos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve01_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve01_codigo = $ve01_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Veiculos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve01_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Veiculos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve01_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve01_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:veiculos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ve01_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from veiculos ";
     $sql .= "      inner join ceplocalidades         on ceplocalidades.cp05_codlocalidades  = veiculos.ve01_ceplocalidades        ";
     $sql .= "      inner join veiccadtipo            on veiccadtipo.ve20_codigo             = veiculos.ve01_veiccadtipo           ";
     $sql .= "      inner join veiccadmarca           on veiccadmarca.ve21_codigo            = veiculos.ve01_veiccadmarca          ";
     $sql .= "      inner join veiccadmodelo          on veiccadmodelo.ve22_codigo           = veiculos.ve01_veiccadmodelo         ";
     $sql .= "      inner join veiccadcor             on veiccadcor.ve23_codigo              = veiculos.ve01_veiccadcor            ";
     $sql .= "      inner join veiccadtipocapacidade  on veiccadtipocapacidade.ve24_codigo   = veiculos.ve01_veiccadtipocapacidade ";
     $sql .= "      inner join veiccadcategcnh        on veiccadcategcnh.ve30_codigo         = veiculos.ve01_veiccadcategcnh       ";
     $sql .= "      inner join veiccadproced          on veiccadproced.ve25_codigo           = veiculos.ve01_veiccadproced         ";
     $sql .= "      inner join veiccadpotencia        on veiccadpotencia.ve31_codigo         = veiculos.ve01_veiccadpotencia       ";
     $sql .= "      inner join veiccadcateg  as a     on a.ve32_codigo                       = veiculos.ve01_veiccadcateg          ";
     $sql .= "      inner join veictipoabast          on veictipoabast.ve07_sequencial       = veiculos.ve01_veictipoabast         ";
     $sql .= "      inner join cepestados             on cepestados.cp03_sigla               = ceplocalidades.cp05_sigla           ";
     $sql .= "      left  join veiccentral            on ve40_veiculos                       = ve01_codigo                         ";
     $sql .= "      left  join veiccadcentraldepart   on ve37_veiccadcentral                 = ve40_veiccadcentral                 ";
     $sql .= "      left  join db_depart              on db_depart.coddepto                  = veiccadcentraldepart.ve37_coddepto  ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve01_codigo!=null ){
         $sql2 .= " where veiculos.ve01_codigo = $ve01_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_central ( $ve01_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from veiculos ";
     $sql .= "      inner join ceplocalidades  on  ceplocalidades.cp05_codlocalidades = veiculos.ve01_ceplocalidades";
     $sql .= "      inner join veiccadtipo  on  veiccadtipo.ve20_codigo = veiculos.ve01_veiccadtipo";
     $sql .= "      inner join veiccadmarca  on  veiccadmarca.ve21_codigo = veiculos.ve01_veiccadmarca";
     $sql .= "      inner join veiccadmodelo  on  veiccadmodelo.ve22_codigo = veiculos.ve01_veiccadmodelo";
     $sql .= "      inner join veiccadcor  on  veiccadcor.ve23_codigo = veiculos.ve01_veiccadcor";
     $sql .= "      inner join veiccadtipocapacidade  on  veiccadtipocapacidade.ve24_codigo = veiculos.ve01_veiccadtipocapacidade";
     $sql .= "      inner join veiccadcategcnh  on  veiccadcategcnh.ve30_codigo = veiculos.ve01_veiccadcategcnh";
     $sql .= "      inner join veiccadproced  on  veiccadproced.ve25_codigo = veiculos.ve01_veiccadproced";
     $sql .= "      inner join veiccadpotencia  on  veiccadpotencia.ve31_codigo = veiculos.ve01_veiccadpotencia";
     $sql .= "      inner join veiccadcateg  as a on   a.ve32_codigo = veiculos.ve01_veiccadcateg";
     $sql .= "      inner join veictipoabast  on  veictipoabast.ve07_sequencial = veiculos.ve01_veictipoabast";
     $sql .= "      inner join cepestados  on  cepestados.cp03_sigla = ceplocalidades.cp05_sigla";
     $sql .= "      inner  join veiccentral  on  veiccentral.ve40_veiculos=veiculos.ve01_codigo ";
     $sql .= "      inner  join veiccadcentral  on  veiccadcentral.ve36_sequencial=veiccentral.ve40_veiccadcentral ";

     $sql2 = "";
     if($dbwhere==""){
       if($ve01_codigo!=null ){
         $sql2 .= " where veiculos.ve01_codigo = $ve01_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $ve01_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from veiculos ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve01_codigo!=null ){
         $sql2 .= " where veiculos.ve01_codigo = $ve01_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
  function sql_query_veiculo ( $ve01_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from veiculos                                                                                                 ";
    $sql .= "   inner join veiculoscomb          on veiculoscomb.ve06_veiculos         = veiculos.ve01_codigo               ";
    $sql .= "   left join veicresp               on veicresp.ve02_veiculo              = veiculos.ve01_codigo               ";
    $sql .= "   left join cgm                    on cgm.z01_numcgm                     = veicresp.ve02_numcgm               ";
    $sql .= "   inner join veiccadcomb           on veiccadcomb.ve26_codigo            = veiculoscomb.ve06_veiccadcomb      ";
    $sql .= "   inner join veiccentral           on veiccentral.ve40_veiculos          = veiculos.ve01_codigo               ";
    $sql .= "   inner join veiccadcentral        on veiccadcentral.ve36_sequencial     = veiccentral.ve40_veiccadcentral    ";
    $sql .= "   inner join db_depart             on db_depart.coddepto                 = veiccadcentral.ve36_coddepto       ";
    $sql .= "   inner join ceplocalidades        on ceplocalidades.cp05_codlocalidades = veiculos.ve01_ceplocalidades       ";
    $sql .= "   inner join veiccadtipo           on veiccadtipo.ve20_codigo            = veiculos.ve01_veiccadtipo          ";
    $sql .= "   inner join veiccadmarca          on veiccadmarca.ve21_codigo           = veiculos.ve01_veiccadmarca         ";
    $sql .= "   inner join veiccadmodelo         on veiccadmodelo.ve22_codigo          = veiculos.ve01_veiccadmodelo        ";
    $sql .= "   inner join veiccadcor            on veiccadcor.ve23_codigo             = veiculos.ve01_veiccadcor           ";
    $sql .= "   inner join veiccadtipocapacidade on veiccadtipocapacidade.ve24_codigo  = veiculos.ve01_veiccadtipocapacidade";
    $sql .= "   inner join veiccadcategcnh       on veiccadcategcnh.ve30_codigo        = veiculos.ve01_veiccadcategcnh      ";
    $sql .= "   inner join veiccadproced         on veiccadproced.ve25_codigo          = veiculos.ve01_veiccadproced        ";
    $sql .= "   inner join veiccadpotencia       on veiccadpotencia.ve31_codigo        = veiculos.ve01_veiccadpotencia      ";
    $sql .= "   inner join veiccadcateg  as a    on a.ve32_codigo                      = veiculos.ve01_veiccadcateg         ";
    $sql .= "   inner join veictipoabast         on veictipoabast.ve07_sequencial      = veiculos.ve01_veictipoabast        ";
    $sql .= "   inner join db_config             on db_config.codigo                   = db_depart.instit                   ";
    $sql .= "   inner join cepestados            on cepestados.cp03_sigla              = ceplocalidades.cp05_sigla          ";
    $sql .= "   left  join veicpatri             on veicpatri.ve03_veiculo             = veiculos.ve01_codigo               ";
    $sql .= "   left  join bens                  on veicpatri.ve03_bem                 = bens.t52_bem                       ";
    $sql .= "   left  join veicbaixa             on veicbaixa.ve04_veiculo             = veiculos.ve01_codigo               ";
    $sql .= "   left  join db_usuarios           on db_usuarios.id_usuario             = veicbaixa.ve04_usuario             ";

    $sql2 = "";

    if($dbwhere==""){
      if($ve01_codigo!=null ){
        $sql2 .= " where veiculos.ve01_codigo = $ve01_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }


  function sql_query_ultimamedida ( $ve01_codigo=null, $dDataBase=null, $sHoraBase=null, $sWhereA=null, $sWhereM=null, $sWhereD=null, $sWhereR=null) {


  	/*
  	 * caso nao seja setado a data e hora pelo usuario, o sistema buscava a data e hora da seção,
  	 * o que ocorria problema, pois as vezes o veiculo estava devolvido ex:
  	 * 18/04/2013 10:00 , e o usuario entraria para proxima devolução
  	 * 18/04/2013 9:45  o sistema buscava a medida errada.
  	 * @todo validar possivel refatoramento no sql para desconsiderar data e hora e buscar diferente a ultima medida.
  	 */
    if(is_null($dDataBase) or trim($dDataBase) == "" or trim($dDataBase) == "--" ){
      //$dDataBase = date("Y-m-d", db_getsession("DB_datausu"));
      $dDataBase = "3000-12-31";
    }

    if(is_null($sHoraBase) or trim($sHoraBase) == ""){
      //$sHoraBase = date("H:m");
      $sHoraBase = "24:59";
    }

    // Select para Buscar Medida da Ultima Retirada, Ultima Devolução ou Ultimo Abastecimento com base numa Data e Hora
    $sqlultimamedida  = "select * ";
    $sqlultimamedida .= "  from ( (select ve70_dtabast    as data, ";
    $sqlultimamedida .= "                 ve70_hora       as hora, ";
    $sqlultimamedida .= "                 ve70_medida     as ultimamedida, ";
    $sqlultimamedida .= "                 'ABASTECIMENTO' as tipo ";
    $sqlultimamedida .= "            from veicabast ";
    $sqlultimamedida .= "           where ve70_veiculos  = {$ve01_codigo} ";
    $sqlultimamedida .= "             and to_timestamp((ve70_dtabast||' '||ve70_hora)::text, 'YYYY-MM-DD HH24:MI') <= ";
    $sqlultimamedida .= "                 to_timestamp('{$dDataBase} {$sHoraBase}'::text,    'YYYY-MM-DD HH24:MI') ";
    $sqlultimamedida .= "             and not exists (select 1 from veicabastanu where ve74_veicabast = ve70_codigo) ";
    $sqlultimamedida .=                   $sWhereA;
    $sqlultimamedida .= "        order by ve70_dtabast desc, ";
    $sqlultimamedida .= "                 ve70_hora    desc, ";
    $sqlultimamedida .= "                 ve70_codigo  desc  ";
    $sqlultimamedida .= "           limit 1) ";

    $sqlultimamedida .= "         union all ";

	  $sqlultimamedida .= "         (select ve62_dtmanut    as data, ";
    $sqlultimamedida .= "                 ve62_hora       as hora, ";
    $sqlultimamedida .= "                 ve62_medida     as ultimamedida, ";
    $sqlultimamedida .= "                 'MANUTENCAO' as tipo ";
    $sqlultimamedida .= "            from veicmanut ";
    $sqlultimamedida .= "           where ve62_veiculos  = {$ve01_codigo} ";
    $sqlultimamedida .= "             and to_timestamp((ve62_dtmanut||' '||ve62_hora)::text, 'YYYY-MM-DD HH24:MI') <= ";
    $sqlultimamedida .= "                 to_timestamp('{$dDataBase} {$sHoraBase}'::text,    'YYYY-MM-DD HH24:MI') ";
    $sqlultimamedida .=                   $sWhereM;
    $sqlultimamedida .= "        order by ve62_dtmanut desc, ";
    $sqlultimamedida .= "                 ve62_hora    desc, ";
    $sqlultimamedida .= "                 ve62_codigo  desc  ";
    $sqlultimamedida .= "           limit 1) ";

    $sqlultimamedida .= "         union all ";

    $sqlultimamedida .= "         (select ve61_datadevol   as data, ";
    $sqlultimamedida .= "                 ve61_horadevol   as hora, ";
    $sqlultimamedida .= "                 ve61_medidadevol as ultimamedida, ";
    $sqlultimamedida .= "                 'DEVOLUCAO'      as tipo ";
    $sqlultimamedida .= "            from veicdevolucao ";
    $sqlultimamedida .= "                 inner join veicretirada on ve60_codigo = ve61_veicretirada ";
    $sqlultimamedida .= "           where ve60_veiculo    = {$ve01_codigo} ";
    $sqlultimamedida .= "             and to_timestamp((ve61_datadevol||' '||ve61_horadevol)::text, 'YYYY-MM-DD HH24:MI') <= ";
    $sqlultimamedida .= "                 to_timestamp('{$dDataBase} {$sHoraBase}'::text,           'YYYY-MM-DD HH24:MI') ";
    $sqlultimamedida .=                   $sWhereD;
    $sqlultimamedida .= "        order by ve61_datadevol desc, ";
    $sqlultimamedida .= "                 ve61_horadevol desc, ";
    $sqlultimamedida .= "                 ve61_codigo    desc ";
    $sqlultimamedida .= "           limit 1) ";

    $sqlultimamedida .= "         union all ";

    $sqlultimamedida .= "         (select ve60_datasaida   as data, ";
    $sqlultimamedida .= "                 ve60_horasaida   as hora, ";
    $sqlultimamedida .= "                 ve60_medidasaida as ultimamedida, ";
    $sqlultimamedida .= "                 'RETIRADA'       as tipo ";
    $sqlultimamedida .= "            from veicretirada ";
    $sqlultimamedida .= "           where ve60_veiculo    = {$ve01_codigo} ";
    $sqlultimamedida .= "             and to_timestamp((ve60_datasaida||' '||ve60_horasaida)::text, 'YYYY-MM-DD HH24:MI') <= ";
    $sqlultimamedida .= "                 to_timestamp('{$dDataBase} {$sHoraBase}'::text,           'YYYY-MM-DD HH24:MI') ";
    $sqlultimamedida .=                   $sWhereR;
    $sqlultimamedida .= "        order by ve60_datasaida desc, ";
    $sqlultimamedida .= "                 ve60_horasaida desc, ";
    $sqlultimamedida .= "                 ve60_codigo    desc ";
    $sqlultimamedida .= "           limit 1) ) as w ";
    $sqlultimamedida .= "  order by 1 desc, ";
    $sqlultimamedida .= "           2 desc, ";
    $sqlultimamedida .= "           3 desc ";
    $sqlultimamedida .= "     limit 1 ";

    //echo "<br>$sqlultimamedida<br>";

    return $sqlultimamedida;
  }

  function sql_query_proximamedida ( $ve01_codigo=null, $dDataBase=null, $sHoraBase=null, $sWhereA=null, $sWhereM=null, $sWhereD=null, $sWhereR=null ) {

    if(is_null($dDataBase) or trim($dDataBase) == "" or trim($dDataBase) == "--" ){
      $dDataBase = date("Y-m-d", db_getsession("DB_datausu"));
    }

    if(is_null($sHoraBase) or trim($sHoraBase) == ""){
      $sHoraBase = date("H:m");
    }

    // Select para Buscar a Proxima Medida após a Retirada, Devolução ou Abastecimento na Data e Hora Base
    $sqlproximamedida  = "select * ";
    $sqlproximamedida .= "  from ( (select ve70_dtabast    as data, ";
    $sqlproximamedida .= "                 ve70_hora       as hora, ";
    $sqlproximamedida .= "                 ve70_medida     as proximamedida, ";
    $sqlproximamedida .= "                 'ABASTECIMENTO' as tipo ";
    $sqlproximamedida .= "            from veicabast ";
    $sqlproximamedida .= "           where ve70_veiculos  = {$ve01_codigo} ";
    $sqlproximamedida .= "             and to_timestamp((ve70_dtabast||' '||ve70_hora)::text, 'YYYY-MM-DD HH24:MI') >= ";
    $sqlproximamedida .= "                 to_timestamp('{$dDataBase} {$sHoraBase}'::text,    'YYYY-MM-DD HH24:MI') ";
    $sqlproximamedida .= "             and not exists (select 1 from veicabastanu where ve74_veicabast = ve70_codigo) ";
    $sqlproximamedida .=                   $sWhereA;
    $sqlproximamedida .= "        order by ve70_dtabast, ";
    $sqlproximamedida .= "                 ve70_hora,    ";
    $sqlproximamedida .= "                 ve70_codigo   ";
    $sqlproximamedida .= "           limit 1) ";

    $sqlproximamedida .= "         union all ";

	  $sqlproximamedida .= "         (select ve62_dtmanut    as data, ";
    $sqlproximamedida .= "                 ve62_hora       as hora, ";
    $sqlproximamedida .= "                 ve62_medida     as proximamedida, ";
    $sqlproximamedida .= "                 'MANUTENCAO' as tipo ";
    $sqlproximamedida .= "            from veicmanut ";
    $sqlproximamedida .= "           where ve62_veiculos  = {$ve01_codigo} ";
    $sqlproximamedida .= "             and to_timestamp((ve62_dtmanut||' '||ve62_hora)::text, 'YYYY-MM-DD HH24:MI') >= ";
    $sqlproximamedida .= "                 to_timestamp('{$dDataBase} {$sHoraBase}'::text,    'YYYY-MM-DD HH24:MI') ";
    $sqlproximamedida .=                   $sWhereM;
    $sqlproximamedida .= "        order by ve62_dtmanut, ";
    $sqlproximamedida .= "                 ve62_hora,    ";
    $sqlproximamedida .= "                 ve62_codigo   ";
    $sqlproximamedida .= "           limit 1) ";

    $sqlproximamedida .= "         union all ";

    $sqlproximamedida .= "         (select ve61_datadevol   as data, ";
    $sqlproximamedida .= "                 ve61_horadevol   as hora, ";
    $sqlproximamedida .= "                 ve61_medidadevol as proximamedida, ";
    $sqlproximamedida .= "                 'DEVOLUCAO'      as tipo ";
    $sqlproximamedida .= "            from veicdevolucao ";
    $sqlproximamedida .= "                 inner join veicretirada on ve60_codigo = ve61_veicretirada ";
    $sqlproximamedida .= "           where ve60_veiculo    = {$ve01_codigo} ";
    $sqlproximamedida .= "             and to_timestamp((ve61_datadevol||' '||ve61_horadevol)::text, 'YYYY-MM-DD HH24:MI') >= ";
    $sqlproximamedida .= "                 to_timestamp('{$dDataBase} {$sHoraBase}'::text,           'YYYY-MM-DD HH24:MI') ";
    $sqlproximamedida .=                   $sWhereD;
    $sqlproximamedida .= "        order by ve61_datadevol, ";
    $sqlproximamedida .= "                 ve61_horadevol, ";
    $sqlproximamedida .= "                 ve61_codigo     ";
    $sqlproximamedida .= "           limit 1) ";

    $sqlproximamedida .= "         union all ";

    $sqlproximamedida .= "         (select ve60_datasaida   as data, ";
    $sqlproximamedida .= "                 ve60_horasaida   as hora, ";
    $sqlproximamedida .= "                 ve60_medidasaida as proximamedida, ";
    $sqlproximamedida .= "                 'RETIRADA'       as tipo ";
    $sqlproximamedida .= "            from veicretirada ";
    $sqlproximamedida .= "           where ve60_veiculo    = {$ve01_codigo} ";
    $sqlproximamedida .= "             and to_timestamp((ve60_datasaida||' '||ve60_horasaida)::text, 'YYYY-MM-DD HH24:MI') >= ";
    $sqlproximamedida .= "                 to_timestamp('{$dDataBase} {$sHoraBase}'::text,           'YYYY-MM-DD HH24:MI') ";
    $sqlproximamedida .=                   $sWhereR;
    $sqlproximamedida .= "        order by ve60_datasaida, ";
    $sqlproximamedida .= "                 ve60_horasaida, ";
    $sqlproximamedida .= "                 ve60_codigo    ";
    $sqlproximamedida .= "           limit 1) ) as w ";
    $sqlproximamedida .= "  order by 1 , ";
    $sqlproximamedida .= "           2 , ";
    $sqlproximamedida .= "           3  ";
    $sqlproximamedida .= "     limit 1 ";

    //echo "<br>$sqlproximamedida<br>";

    return $sqlproximamedida;
  }


  function sql_query_medida_between ($ve01_codigo=null, $dDataInicial, $sHoraInicial, $dDataFinal, $sHoraFinal) {

    // Select para Buscar Medida da Ultima Retirada, Ultima Devolução ou Ultimo Abastecimento com base numa Data e Hora
    $sqlultimamedida  = "select * ";
    $sqlultimamedida .= "  from ( (select ve70_dtabast    as data, ";
    $sqlultimamedida .= "                 ve70_hora       as hora, ";
    $sqlultimamedida .= "                 ve70_medida     as ultimamedida, ";
    $sqlultimamedida .= "                 'ABASTECIMENTO' as tipo ";
    $sqlultimamedida .= "            from veicabast ";
    $sqlultimamedida .= "           where ve70_veiculos  = {$ve01_codigo} ";
    $sqlultimamedida .= "             and to_timestamp((ve70_dtabast||' '||ve70_hora)::text, 'YYYY-MM-DD HH24:MI') ";
    $sqlultimamedida .= "         between to_timestamp('{$dDataInicial} {$sHoraInicial}'::text,    'YYYY-MM-DD HH24:MI') ";
    $sqlultimamedida .= "             and to_timestamp('{$dDataFinal} {$sHoraFinal}'::text,    'YYYY-MM-DD HH24:MI') ";
    $sqlultimamedida .= "             and not exists (select 1 from veicabastanu where ve74_veicabast = ve70_codigo) ";
    $sqlultimamedida .= "        order by ve70_dtabast desc, ";
    $sqlultimamedida .= "                 ve70_hora    desc, ";
    $sqlultimamedida .= "                 ve70_codigo  desc  ";
    $sqlultimamedida .= "           limit 1) ";

    $sqlultimamedida .= "         union all ";

    $sqlultimamedida .= "         (select ve62_dtmanut    as data, ";
    $sqlultimamedida .= "                 ve62_hora       as hora, ";
    $sqlultimamedida .= "                 ve62_medida     as ultimamedida, ";
    $sqlultimamedida .= "                 'MANUTENCAO' as tipo ";
    $sqlultimamedida .= "            from veicmanut ";
    $sqlultimamedida .= "           where ve62_veiculos  = {$ve01_codigo} ";
    $sqlultimamedida .= "             and to_timestamp((ve62_dtmanut||' '||ve62_hora)::text, 'YYYY-MM-DD HH24:MI')";
    $sqlultimamedida .= "         between to_timestamp('{$dDataInicial} {$sHoraInicial}'::text,    'YYYY-MM-DD HH24:MI') ";
    $sqlultimamedida .= "             and to_timestamp('{$dDataFinal} {$sHoraFinal}'::text,    'YYYY-MM-DD HH24:MI') ";
    $sqlultimamedida .= "        order by ve62_dtmanut desc, ";
    $sqlultimamedida .= "                 ve62_hora    desc, ";
    $sqlultimamedida .= "                 ve62_codigo  desc  ";
    $sqlultimamedida .= "           limit 1) ";

    $sqlultimamedida .= "         union all ";

    $sqlultimamedida .= "         (select ve61_datadevol   as data, ";
    $sqlultimamedida .= "                 ve61_horadevol   as hora, ";
    $sqlultimamedida .= "                 ve61_medidadevol as ultimamedida, ";
    $sqlultimamedida .= "                 'DEVOLUCAO'      as tipo ";
    $sqlultimamedida .= "            from veicdevolucao ";
    $sqlultimamedida .= "                 inner join veicretirada on ve60_codigo = ve61_veicretirada ";
    $sqlultimamedida .= "           where ve60_veiculo    = {$ve01_codigo} ";
    $sqlultimamedida .= "             and to_timestamp((ve61_datadevol||' '||ve61_horadevol)::text, 'YYYY-MM-DD HH24:MI')";
    $sqlultimamedida .= "         between to_timestamp('{$dDataInicial} {$sHoraInicial}'::text,    'YYYY-MM-DD HH24:MI') ";
    $sqlultimamedida .= "             and to_timestamp('{$dDataFinal} {$sHoraFinal}'::text,    'YYYY-MM-DD HH24:MI') ";
    $sqlultimamedida .= "        order by ve61_datadevol desc, ";
    $sqlultimamedida .= "                 ve61_horadevol desc, ";
    $sqlultimamedida .= "                 ve61_codigo    desc ";
    $sqlultimamedida .= "           limit 1) ";

    $sqlultimamedida .= "         union all ";

    $sqlultimamedida .= "         (select ve60_datasaida   as data, ";
    $sqlultimamedida .= "                 ve60_horasaida   as hora, ";
    $sqlultimamedida .= "                 ve60_medidasaida as ultimamedida, ";
    $sqlultimamedida .= "                 'RETIRADA'       as tipo ";
    $sqlultimamedida .= "            from veicretirada ";
    $sqlultimamedida .= "           where ve60_veiculo    = {$ve01_codigo} ";
    $sqlultimamedida .= "             and to_timestamp((ve60_datasaida||' '||ve60_horasaida)::text, 'YYYY-MM-DD HH24:MI') ";
    $sqlultimamedida .= "         between to_timestamp('{$dDataInicial} {$sHoraInicial}'::text,    'YYYY-MM-DD HH24:MI') ";
    $sqlultimamedida .= "             and to_timestamp('{$dDataFinal} {$sHoraFinal}'::text,    'YYYY-MM-DD HH24:MI') ";
    $sqlultimamedida .= "        order by ve60_datasaida desc, ";
    $sqlultimamedida .= "                 ve60_horasaida desc, ";
    $sqlultimamedida .= "                 ve60_codigo    desc ";
    $sqlultimamedida .= "           limit 1) ) as w ";
    $sqlultimamedida .= "  order by 1 desc, ";
    $sqlultimamedida .= "           2 desc, ";
    $sqlultimamedida .= "           3 desc ";
    $sqlultimamedida .= "     limit 1 ";
    return $sqlultimamedida;
  }

   public function sql_query_modelo( $ve01_codigo = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

     $sql  = "select {$campos} ";
     $sql .= "  from veiculos  ";
     $sql .= " inner join veiccadmodelo on veiccadmodelo.ve22_codigo = veiculos.ve01_veiccadmodelo ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ve01_codigo)){
         $sql2 .= " where tipohoratrabalho.ve01_codigo = $ve01_codigo ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

  public function sql_query_movimentos($iCodigo = null, $sCampos = "*", $sOrder = "", $sWhere = "", $sGroup = "") {

    $sql  = " select {$sCampos} ";
    $sql .= " from ";
    $sql .= " veiculos ";
    $sql .= "   inner join veiccadmodelo        on veiccadmodelo.ve22_codigo                = veiculos.ve01_veiccadmodelo ";
    $sql .= "   inner join veiccentral          on veiccentral.ve40_veiculos                = veiculos.ve01_codigo ";
    $sql .= "   inner join veiccadcentral       on veiccadcentral.ve36_sequencial           = veiccentral.ve40_veiccadcentral ";
    $sql .= "   left  join veiccadcentraldepart on veiccadcentraldepart.ve37_veiccadcentral = veiccadcentral.ve36_sequencial ";
    $sql .= "   inner join db_depart            on db_depart.coddepto                       = veiccadcentral.ve36_coddepto ";
    $sql .= "   inner join db_config            on db_config.codigo                         = db_depart.instit ";
    $sql .= "   left join  veicmanut            on veiculos.ve01_codigo                     = veicmanut.ve62_veiculos ";
    $sql .= "   left join  veicabast            on veiculos.ve01_codigo                     = veicabast.ve70_veiculos ";
    $sql .= "   left join  veicretirada         on veiculos.ve01_codigo                     = veicretirada.ve60_veiculo ";
    $sql .= "   left join  veicdevolucao        on veicretirada.ve60_codigo                 = veicdevolucao.ve61_veicretirada ";
    $sql .= "   left join  veicpatri            on veiculos.ve01_codigo                     = veicpatri.ve03_veiculo ";
    $sql .= "   left join  bens                 on bens.t52_bem                             = veicpatri.ve03_bem ";
    $sql .= "   left join  veiculoscomb         on veiculos.ve01_codigo                     = veiculoscomb.ve06_veiculos ";

    if (empty($sWhere) && $iCodigo != null) {
      $sWhere = " ve01_codigo = {$iCodigo} ";
    }

    $sql .= " where {$sWhere} ";

    if (!empty($sGroup)) {
      $sql .= " group by {$sGroup} ";
    }

    if (!empty($sOrder)) {
      $sql .= " order by {$sOrder} ";
    }

    return $sql;
  }
}
