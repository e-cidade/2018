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

//MODULO: pessoal
//CLASSE DA ENTIDADE afastamento
class cl_afastamento { 
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
   var $r69_anousu = 0; 
   var $r69_mesusu = 0; 
   var $r69_regist = 0; 
   var $r69_codigo = 0; 
   var $r69_dtafast_dia = null; 
   var $r69_dtafast_mes = null; 
   var $r69_dtafast_ano = null; 
   var $r69_dtafast = null; 
   var $r69_dtretorno_dia = null; 
   var $r69_dtretorno_mes = null; 
   var $r69_dtretorno_ano = null; 
   var $r69_dtretorno = null; 
   var $r69_dtlanc_dia = null; 
   var $r69_dtlanc_mes = null; 
   var $r69_dtlanc_ano = null; 
   var $r69_dtlanc = null; 
   var $r69_login = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r69_anousu = int4 = Exercício 
                 r69_mesusu = int4 = Mês do Exercício 
                 r69_regist = int4 = Número Registro 
                 r69_codigo = int4 = Código do Afastamento 
                 r69_dtafast = date = Início do Afastamento 
                 r69_dtretorno = date = Final do Afastamento 
                 r69_dtlanc = date = Data do Lançamento 
                 r69_login = varchar(8) = Usuário 
                 ";
   //funcao construtor da classe 
   function cl_afastamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("afastamento"); 
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
       $this->r69_anousu = ($this->r69_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r69_anousu"]:$this->r69_anousu);
       $this->r69_mesusu = ($this->r69_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r69_mesusu"]:$this->r69_mesusu);
       $this->r69_regist = ($this->r69_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r69_regist"]:$this->r69_regist);
       $this->r69_codigo = ($this->r69_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r69_codigo"]:$this->r69_codigo);
       if($this->r69_dtafast == ""){
         $this->r69_dtafast_dia = ($this->r69_dtafast_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r69_dtafast_dia"]:$this->r69_dtafast_dia);
         $this->r69_dtafast_mes = ($this->r69_dtafast_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r69_dtafast_mes"]:$this->r69_dtafast_mes);
         $this->r69_dtafast_ano = ($this->r69_dtafast_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r69_dtafast_ano"]:$this->r69_dtafast_ano);
         if($this->r69_dtafast_dia != ""){
            $this->r69_dtafast = $this->r69_dtafast_ano."-".$this->r69_dtafast_mes."-".$this->r69_dtafast_dia;
         }
       }
       if($this->r69_dtretorno == ""){
         $this->r69_dtretorno_dia = ($this->r69_dtretorno_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r69_dtretorno_dia"]:$this->r69_dtretorno_dia);
         $this->r69_dtretorno_mes = ($this->r69_dtretorno_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r69_dtretorno_mes"]:$this->r69_dtretorno_mes);
         $this->r69_dtretorno_ano = ($this->r69_dtretorno_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r69_dtretorno_ano"]:$this->r69_dtretorno_ano);
         if($this->r69_dtretorno_dia != ""){
            $this->r69_dtretorno = $this->r69_dtretorno_ano."-".$this->r69_dtretorno_mes."-".$this->r69_dtretorno_dia;
         }
       }
       if($this->r69_dtlanc == ""){
         $this->r69_dtlanc_dia = ($this->r69_dtlanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r69_dtlanc_dia"]:$this->r69_dtlanc_dia);
         $this->r69_dtlanc_mes = ($this->r69_dtlanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r69_dtlanc_mes"]:$this->r69_dtlanc_mes);
         $this->r69_dtlanc_ano = ($this->r69_dtlanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r69_dtlanc_ano"]:$this->r69_dtlanc_ano);
         if($this->r69_dtlanc_dia != ""){
            $this->r69_dtlanc = $this->r69_dtlanc_ano."-".$this->r69_dtlanc_mes."-".$this->r69_dtlanc_dia;
         }
       }
       $this->r69_login = ($this->r69_login == ""?@$GLOBALS["HTTP_POST_VARS"]["r69_login"]:$this->r69_login);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->r69_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "r69_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r69_mesusu == null ){ 
       $this->erro_sql = " Campo Mês do Exercício nao Informado.";
       $this->erro_campo = "r69_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r69_regist == null ){ 
       $this->erro_sql = " Campo Número Registro nao Informado.";
       $this->erro_campo = "r69_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r69_codigo == null ){ 
       $this->erro_sql = " Campo Código do Afastamento nao Informado.";
       $this->erro_campo = "r69_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r69_dtafast == null ){ 
       $this->erro_sql = " Campo Início do Afastamento nao Informado.";
       $this->erro_campo = "r69_dtafast_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r69_dtretorno == null ){ 
       $this->erro_sql = " Campo Final do Afastamento nao Informado.";
       $this->erro_campo = "r69_dtretorno_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r69_dtlanc == null ){ 
       $this->erro_sql = " Campo Data do Lançamento nao Informado.";
       $this->erro_campo = "r69_dtlanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r69_login == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "r69_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into afastamento(
                                       r69_anousu 
                                      ,r69_mesusu 
                                      ,r69_regist 
                                      ,r69_codigo 
                                      ,r69_dtafast 
                                      ,r69_dtretorno 
                                      ,r69_dtlanc 
                                      ,r69_login 
                       )
                values (
                                $this->r69_anousu 
                               ,$this->r69_mesusu 
                               ,$this->r69_regist 
                               ,$this->r69_codigo 
                               ,".($this->r69_dtafast == "null" || $this->r69_dtafast == ""?"null":"'".$this->r69_dtafast."'")." 
                               ,".($this->r69_dtretorno == "null" || $this->r69_dtretorno == ""?"null":"'".$this->r69_dtretorno."'")." 
                               ,".($this->r69_dtlanc == "null" || $this->r69_dtlanc == ""?"null":"'".$this->r69_dtlanc."'")." 
                               ,'$this->r69_login' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Afastamento Informativo () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Afastamento Informativo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Afastamento Informativo () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update afastamento set ";
     $virgula = "";
     if(trim($this->r69_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r69_anousu"])){ 
       $sql  .= $virgula." r69_anousu = $this->r69_anousu ";
       $virgula = ",";
       if(trim($this->r69_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "r69_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r69_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r69_mesusu"])){ 
       $sql  .= $virgula." r69_mesusu = $this->r69_mesusu ";
       $virgula = ",";
       if(trim($this->r69_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês do Exercício nao Informado.";
         $this->erro_campo = "r69_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r69_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r69_regist"])){ 
       $sql  .= $virgula." r69_regist = $this->r69_regist ";
       $virgula = ",";
       if(trim($this->r69_regist) == null ){ 
         $this->erro_sql = " Campo Número Registro nao Informado.";
         $this->erro_campo = "r69_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r69_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r69_codigo"])){ 
       $sql  .= $virgula." r69_codigo = $this->r69_codigo ";
       $virgula = ",";
       if(trim($this->r69_codigo) == null ){ 
         $this->erro_sql = " Campo Código do Afastamento nao Informado.";
         $this->erro_campo = "r69_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r69_dtafast)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r69_dtafast_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r69_dtafast_dia"] !="") ){ 
       $sql  .= $virgula." r69_dtafast = '$this->r69_dtafast' ";
       $virgula = ",";
       if(trim($this->r69_dtafast) == null ){ 
         $this->erro_sql = " Campo Início do Afastamento nao Informado.";
         $this->erro_campo = "r69_dtafast_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r69_dtafast_dia"])){ 
         $sql  .= $virgula." r69_dtafast = null ";
         $virgula = ",";
         if(trim($this->r69_dtafast) == null ){ 
           $this->erro_sql = " Campo Início do Afastamento nao Informado.";
           $this->erro_campo = "r69_dtafast_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r69_dtretorno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r69_dtretorno_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r69_dtretorno_dia"] !="") ){ 
       $sql  .= $virgula." r69_dtretorno = '$this->r69_dtretorno' ";
       $virgula = ",";
       if(trim($this->r69_dtretorno) == null ){ 
         $this->erro_sql = " Campo Final do Afastamento nao Informado.";
         $this->erro_campo = "r69_dtretorno_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r69_dtretorno_dia"])){ 
         $sql  .= $virgula." r69_dtretorno = null ";
         $virgula = ",";
         if(trim($this->r69_dtretorno) == null ){ 
           $this->erro_sql = " Campo Final do Afastamento nao Informado.";
           $this->erro_campo = "r69_dtretorno_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r69_dtlanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r69_dtlanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r69_dtlanc_dia"] !="") ){ 
       $sql  .= $virgula." r69_dtlanc = '$this->r69_dtlanc' ";
       $virgula = ",";
       if(trim($this->r69_dtlanc) == null ){ 
         $this->erro_sql = " Campo Data do Lançamento nao Informado.";
         $this->erro_campo = "r69_dtlanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r69_dtlanc_dia"])){ 
         $sql  .= $virgula." r69_dtlanc = null ";
         $virgula = ",";
         if(trim($this->r69_dtlanc) == null ){ 
           $this->erro_sql = " Campo Data do Lançamento nao Informado.";
           $this->erro_campo = "r69_dtlanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r69_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r69_login"])){ 
       $sql  .= $virgula." r69_login = '$this->r69_login' ";
       $virgula = ",";
       if(trim($this->r69_login) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "r69_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Afastamento Informativo nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Afastamento Informativo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from afastamento
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
       $this->erro_sql   = "Afastamento Informativo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Afastamento Informativo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:afastamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>