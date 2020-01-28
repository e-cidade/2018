<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: agua
//CLASSE DA ENTIDADE aguaconf
class cl_aguaconf { 
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
   var $x18_anousu = 0; 
   var $x18_carsemesgoto = 0; 
   var $x18_carsemagua = 0; 
   var $x18_arretipo = 0; 
   var $x18_caresgoto = 0; 
   var $x18_caragua = 0; 
   var $x18_consumoexcesso = 0; 
   var $x18_consumoesgoto = 0; 
   var $x18_consumoagua = 0; 
   var $x18_cartipoimovei = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x18_anousu = int4 = Exerc�cio 
                 x18_carsemesgoto = int4 = Caracter�stica Sem Esgoto 
                 x18_carsemagua = int4 = Caracter�stica Sem �gua 
                 x18_arretipo = int4 = Tipo de D�bito 
                 x18_caresgoto = int4 = Caracter�stica Esgoto 
                 x18_caragua = int4 = Caracter�stica �gua 
                 x18_consumoexcesso = int4 = Tipo Consumo Excesso 
                 x18_consumoesgoto = int4 = Tipo Consumo Esgoto 
                 x18_consumoagua = int4 = Tipo Consumo �gua 
                 x18_cartipoimovei = int4 = Caracter�stica Tipo Im�vel 
                 ";
   //funcao construtor da classe 
   function cl_aguaconf() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguaconf"); 
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
       $this->x18_anousu = ($this->x18_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["x18_anousu"]:$this->x18_anousu);
       $this->x18_carsemesgoto = ($this->x18_carsemesgoto == ""?@$GLOBALS["HTTP_POST_VARS"]["x18_carsemesgoto"]:$this->x18_carsemesgoto);
       $this->x18_carsemagua = ($this->x18_carsemagua == ""?@$GLOBALS["HTTP_POST_VARS"]["x18_carsemagua"]:$this->x18_carsemagua);
       $this->x18_arretipo = ($this->x18_arretipo == ""?@$GLOBALS["HTTP_POST_VARS"]["x18_arretipo"]:$this->x18_arretipo);
       $this->x18_caresgoto = ($this->x18_caresgoto == ""?@$GLOBALS["HTTP_POST_VARS"]["x18_caresgoto"]:$this->x18_caresgoto);
       $this->x18_caragua = ($this->x18_caragua == ""?@$GLOBALS["HTTP_POST_VARS"]["x18_caragua"]:$this->x18_caragua);
       $this->x18_consumoexcesso = ($this->x18_consumoexcesso == ""?@$GLOBALS["HTTP_POST_VARS"]["x18_consumoexcesso"]:$this->x18_consumoexcesso);
       $this->x18_consumoesgoto = ($this->x18_consumoesgoto == ""?@$GLOBALS["HTTP_POST_VARS"]["x18_consumoesgoto"]:$this->x18_consumoesgoto);
       $this->x18_consumoagua = ($this->x18_consumoagua == ""?@$GLOBALS["HTTP_POST_VARS"]["x18_consumoagua"]:$this->x18_consumoagua);
       $this->x18_cartipoimovei = ($this->x18_cartipoimovei == ""?@$GLOBALS["HTTP_POST_VARS"]["x18_cartipoimovei"]:$this->x18_cartipoimovei);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->x18_anousu == null ){ 
       $this->erro_sql = " Campo Exerc�cio nao Informado.";
       $this->erro_campo = "x18_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x18_carsemesgoto == null ){ 
       $this->erro_sql = " Campo Caracter�stica Sem Esgoto nao Informado.";
       $this->erro_campo = "x18_carsemesgoto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x18_carsemagua == null ){ 
       $this->erro_sql = " Campo Caracter�stica Sem �gua nao Informado.";
       $this->erro_campo = "x18_carsemagua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x18_arretipo == null ){ 
       $this->erro_sql = " Campo Tipo de D�bito nao Informado.";
       $this->erro_campo = "x18_arretipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x18_caresgoto == null ){ 
       $this->erro_sql = " Campo Caracter�stica Esgoto nao Informado.";
       $this->erro_campo = "x18_caresgoto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x18_caragua == null ){ 
       $this->erro_sql = " Campo Caracter�stica �gua nao Informado.";
       $this->erro_campo = "x18_caragua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x18_consumoexcesso == null ){ 
       $this->erro_sql = " Campo Tipo Consumo Excesso nao Informado.";
       $this->erro_campo = "x18_consumoexcesso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x18_consumoesgoto == null ){ 
       $this->erro_sql = " Campo Tipo Consumo Esgoto nao Informado.";
       $this->erro_campo = "x18_consumoesgoto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x18_consumoagua == null ){ 
       $this->erro_sql = " Campo Tipo Consumo �gua nao Informado.";
       $this->erro_campo = "x18_consumoagua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x18_cartipoimovei == null ){ 
       $this->erro_sql = " Campo Caracter�stica Tipo Im�vel nao Informado.";
       $this->erro_campo = "x18_cartipoimovei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguaconf(
                                       x18_anousu 
                                      ,x18_carsemesgoto 
                                      ,x18_carsemagua 
                                      ,x18_arretipo 
                                      ,x18_caresgoto 
                                      ,x18_caragua 
                                      ,x18_consumoexcesso 
                                      ,x18_consumoesgoto 
                                      ,x18_consumoagua 
                                      ,x18_cartipoimovei 
                       )
                values (
                                $this->x18_anousu 
                               ,$this->x18_carsemesgoto 
                               ,$this->x18_carsemagua 
                               ,$this->x18_arretipo 
                               ,$this->x18_caresgoto 
                               ,$this->x18_caragua 
                               ,$this->x18_consumoexcesso 
                               ,$this->x18_consumoesgoto 
                               ,$this->x18_consumoagua 
                               ,$this->x18_cartipoimovei 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parametros () nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parametros j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parametros () nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update aguaconf set ";
     $virgula = "";
     if(trim($this->x18_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x18_anousu"])){ 
       $sql  .= $virgula." x18_anousu = $this->x18_anousu ";
       $virgula = ",";
       if(trim($this->x18_anousu) == null ){ 
         $this->erro_sql = " Campo Exerc�cio nao Informado.";
         $this->erro_campo = "x18_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x18_carsemesgoto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x18_carsemesgoto"])){ 
       $sql  .= $virgula." x18_carsemesgoto = $this->x18_carsemesgoto ";
       $virgula = ",";
       if(trim($this->x18_carsemesgoto) == null ){ 
         $this->erro_sql = " Campo Caracter�stica Sem Esgoto nao Informado.";
         $this->erro_campo = "x18_carsemesgoto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x18_carsemagua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x18_carsemagua"])){ 
       $sql  .= $virgula." x18_carsemagua = $this->x18_carsemagua ";
       $virgula = ",";
       if(trim($this->x18_carsemagua) == null ){ 
         $this->erro_sql = " Campo Caracter�stica Sem �gua nao Informado.";
         $this->erro_campo = "x18_carsemagua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x18_arretipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x18_arretipo"])){ 
       $sql  .= $virgula." x18_arretipo = $this->x18_arretipo ";
       $virgula = ",";
       if(trim($this->x18_arretipo) == null ){ 
         $this->erro_sql = " Campo Tipo de D�bito nao Informado.";
         $this->erro_campo = "x18_arretipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x18_caresgoto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x18_caresgoto"])){ 
       $sql  .= $virgula." x18_caresgoto = $this->x18_caresgoto ";
       $virgula = ",";
       if(trim($this->x18_caresgoto) == null ){ 
         $this->erro_sql = " Campo Caracter�stica Esgoto nao Informado.";
         $this->erro_campo = "x18_caresgoto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x18_caragua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x18_caragua"])){ 
       $sql  .= $virgula." x18_caragua = $this->x18_caragua ";
       $virgula = ",";
       if(trim($this->x18_caragua) == null ){ 
         $this->erro_sql = " Campo Caracter�stica �gua nao Informado.";
         $this->erro_campo = "x18_caragua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x18_consumoexcesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x18_consumoexcesso"])){ 
       $sql  .= $virgula." x18_consumoexcesso = $this->x18_consumoexcesso ";
       $virgula = ",";
       if(trim($this->x18_consumoexcesso) == null ){ 
         $this->erro_sql = " Campo Tipo Consumo Excesso nao Informado.";
         $this->erro_campo = "x18_consumoexcesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x18_consumoesgoto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x18_consumoesgoto"])){ 
       $sql  .= $virgula." x18_consumoesgoto = $this->x18_consumoesgoto ";
       $virgula = ",";
       if(trim($this->x18_consumoesgoto) == null ){ 
         $this->erro_sql = " Campo Tipo Consumo Esgoto nao Informado.";
         $this->erro_campo = "x18_consumoesgoto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x18_consumoagua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x18_consumoagua"])){ 
       $sql  .= $virgula." x18_consumoagua = $this->x18_consumoagua ";
       $virgula = ",";
       if(trim($this->x18_consumoagua) == null ){ 
         $this->erro_sql = " Campo Tipo Consumo �gua nao Informado.";
         $this->erro_campo = "x18_consumoagua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x18_cartipoimovei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x18_cartipoimovei"])){ 
       $sql  .= $virgula." x18_cartipoimovei = $this->x18_cartipoimovei ";
       $virgula = ",";
       if(trim($this->x18_cartipoimovei) == null ){ 
         $this->erro_sql = " Campo Caracter�stica Tipo Im�vel nao Informado.";
         $this->erro_campo = "x18_cartipoimovei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from aguaconf
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:aguaconf";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>