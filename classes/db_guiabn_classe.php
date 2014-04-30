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

//MODULO: dbicms
//CLASSE DA ENTIDADE guiabn
class cl_guiabn { 
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
   var $anousu = 0; 
   var $cgcter = null; 
   var $cgcte = null; 
   var $ref = null; 
   var $dti_dia = null; 
   var $dti_mes = null; 
   var $dti_ano = null; 
   var $dti = null; 
   var $dtf_dia = null; 
   var $dtf_mes = null; 
   var $dtf_ano = null; 
   var $dtf = null; 
   var $dtve_dia = null; 
   var $dtve_mes = null; 
   var $dtve_ano = null; 
   var $dtve = null; 
   var $valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 anousu = int4 = Exercício 
                 cgcter = char(3) = Codigo do Município 
                 cgcte = char(7) = Cadastro no Tesouro do Estado 
                 ref = char(3) = Referência 
                 dti = date = Data Inicial 
                 dtf = date = Data Final 
                 dtve = date = Data do Vencimento 
                 valor = float8 = valor 
                 ";
   //funcao construtor da classe 
   function cl_guiabn() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("guiabn"); 
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
       $this->anousu = ($this->anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["anousu"]:$this->anousu);
       $this->cgcter = ($this->cgcter == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcter"]:$this->cgcter);
       $this->cgcte = ($this->cgcte == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcte"]:$this->cgcte);
       $this->ref = ($this->ref == ""?@$GLOBALS["HTTP_POST_VARS"]["ref"]:$this->ref);
       if($this->dti == ""){
         $this->dti_dia = ($this->dti_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dti_dia"]:$this->dti_dia);
         $this->dti_mes = ($this->dti_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dti_mes"]:$this->dti_mes);
         $this->dti_ano = ($this->dti_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dti_ano"]:$this->dti_ano);
         if($this->dti_dia != ""){
            $this->dti = $this->dti_ano."-".$this->dti_mes."-".$this->dti_dia;
         }
       }
       if($this->dtf == ""){
         $this->dtf_dia = ($this->dtf_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dtf_dia"]:$this->dtf_dia);
         $this->dtf_mes = ($this->dtf_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dtf_mes"]:$this->dtf_mes);
         $this->dtf_ano = ($this->dtf_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dtf_ano"]:$this->dtf_ano);
         if($this->dtf_dia != ""){
            $this->dtf = $this->dtf_ano."-".$this->dtf_mes."-".$this->dtf_dia;
         }
       }
       if($this->dtve == ""){
         $this->dtve_dia = ($this->dtve_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dtve_dia"]:$this->dtve_dia);
         $this->dtve_mes = ($this->dtve_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dtve_mes"]:$this->dtve_mes);
         $this->dtve_ano = ($this->dtve_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dtve_ano"]:$this->dtve_ano);
         if($this->dtve_dia != ""){
            $this->dtve = $this->dtve_ano."-".$this->dtve_mes."-".$this->dtve_dia;
         }
       }
       $this->valor = ($this->valor == ""?@$GLOBALS["HTTP_POST_VARS"]["valor"]:$this->valor);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cgcter == null ){ 
       $this->erro_sql = " Campo Codigo do Município nao Informado.";
       $this->erro_campo = "cgcter";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cgcte == null ){ 
       $this->erro_sql = " Campo Cadastro no Tesouro do Estado nao Informado.";
       $this->erro_campo = "cgcte";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ref == null ){ 
       $this->erro_sql = " Campo Referência nao Informado.";
       $this->erro_campo = "ref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dti == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "dti_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dtf == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "dtf_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dtve == null ){ 
       $this->erro_sql = " Campo Data do Vencimento nao Informado.";
       $this->erro_campo = "dtve_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->valor == null ){ 
       $this->erro_sql = " Campo valor nao Informado.";
       $this->erro_campo = "valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into guiabn(
                                       anousu 
                                      ,cgcter 
                                      ,cgcte 
                                      ,ref 
                                      ,dti 
                                      ,dtf 
                                      ,dtve 
                                      ,valor 
                       )
                values (
                                $this->anousu 
                               ,'$this->cgcter' 
                               ,'$this->cgcte' 
                               ,'$this->ref' 
                               ,".($this->dti == "null" || $this->dti == ""?"null":"'".$this->dti."'")." 
                               ,".($this->dtf == "null" || $this->dtf == ""?"null":"'".$this->dtf."'")." 
                               ,".($this->dtve == "null" || $this->dtve == ""?"null":"'".$this->dtve."'")." 
                               ,$this->valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Guiabn () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Guiabn já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Guiabn () nao Incluído. Inclusao Abortada.";
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
     $sql = " update guiabn set ";
     $virgula = "";
     if(trim($this->anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["anousu"])){ 
       $sql  .= $virgula." anousu = $this->anousu ";
       $virgula = ",";
       if(trim($this->anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cgcter)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cgcter"])){ 
       $sql  .= $virgula." cgcter = '$this->cgcter' ";
       $virgula = ",";
       if(trim($this->cgcter) == null ){ 
         $this->erro_sql = " Campo Codigo do Município nao Informado.";
         $this->erro_campo = "cgcter";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cgcte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cgcte"])){ 
       $sql  .= $virgula." cgcte = '$this->cgcte' ";
       $virgula = ",";
       if(trim($this->cgcte) == null ){ 
         $this->erro_sql = " Campo Cadastro no Tesouro do Estado nao Informado.";
         $this->erro_campo = "cgcte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ref"])){ 
       $sql  .= $virgula." ref = '$this->ref' ";
       $virgula = ",";
       if(trim($this->ref) == null ){ 
         $this->erro_sql = " Campo Referência nao Informado.";
         $this->erro_campo = "ref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dti)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dti_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dti_dia"] !="") ){ 
       $sql  .= $virgula." dti = '$this->dti' ";
       $virgula = ",";
       if(trim($this->dti) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "dti_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dti_dia"])){ 
         $sql  .= $virgula." dti = null ";
         $virgula = ",";
         if(trim($this->dti) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "dti_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->dtf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dtf_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dtf_dia"] !="") ){ 
       $sql  .= $virgula." dtf = '$this->dtf' ";
       $virgula = ",";
       if(trim($this->dtf) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "dtf_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dtf_dia"])){ 
         $sql  .= $virgula." dtf = null ";
         $virgula = ",";
         if(trim($this->dtf) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "dtf_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->dtve)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dtve_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dtve_dia"] !="") ){ 
       $sql  .= $virgula." dtve = '$this->dtve' ";
       $virgula = ",";
       if(trim($this->dtve) == null ){ 
         $this->erro_sql = " Campo Data do Vencimento nao Informado.";
         $this->erro_campo = "dtve_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dtve_dia"])){ 
         $sql  .= $virgula." dtve = null ";
         $virgula = ",";
         if(trim($this->dtve) == null ){ 
           $this->erro_sql = " Campo Data do Vencimento nao Informado.";
           $this->erro_campo = "dtve_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["valor"])){ 
       $sql  .= $virgula." valor = $this->valor ";
       $virgula = ",";
       if(trim($this->valor) == null ){ 
         $this->erro_sql = " Campo valor nao Informado.";
         $this->erro_campo = "valor";
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
       $this->erro_sql   = "Guiabn nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Guiabn nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from guiabn
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
       $this->erro_sql   = "Guiabn nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Guiabn nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:guiabn";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>