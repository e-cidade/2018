<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: compras
//CLASSE DA ENTIDADE pcorcamval
class cl_pcorcamval { 
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
   var $pc23_orcamforne = 0; 
   var $pc23_orcamitem = 0; 
   var $pc23_valor = 0; 
   var $pc23_quant = 0; 
   var $pc23_obs = null; 
   var $pc23_vlrun = 0; 
   var $pc23_validmin_dia = null; 
   var $pc23_validmin_mes = null; 
   var $pc23_validmin_ano = null; 
   var $pc23_validmin = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc23_orcamforne = int8 = C�digo do orcamento deste fornecedor 
                 pc23_orcamitem = int4 = C�digo sequencial do item no or�amento 
                 pc23_valor = float8 = Valor or�ado 
                 pc23_quant = float8 = Quantidade or�ada 
                 pc23_obs = text = Obs 
                 pc23_vlrun = float8 = Valor unit�rio 
                 pc23_validmin = date = Validade Minima 
                 ";
   //funcao construtor da classe 
   function cl_pcorcamval() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcorcamval"); 
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
       $this->pc23_orcamforne = ($this->pc23_orcamforne == ""?@$GLOBALS["HTTP_POST_VARS"]["pc23_orcamforne"]:$this->pc23_orcamforne);
       $this->pc23_orcamitem = ($this->pc23_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc23_orcamitem"]:$this->pc23_orcamitem);
       $this->pc23_valor = ($this->pc23_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["pc23_valor"]:$this->pc23_valor);
       $this->pc23_quant = ($this->pc23_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["pc23_quant"]:$this->pc23_quant);
       $this->pc23_obs = ($this->pc23_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["pc23_obs"]:$this->pc23_obs);
       $this->pc23_vlrun = ($this->pc23_vlrun == ""?@$GLOBALS["HTTP_POST_VARS"]["pc23_vlrun"]:$this->pc23_vlrun);
       if($this->pc23_validmin == ""){
         $this->pc23_validmin_dia = ($this->pc23_validmin_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc23_validmin_dia"]:$this->pc23_validmin_dia);
         $this->pc23_validmin_mes = ($this->pc23_validmin_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc23_validmin_mes"]:$this->pc23_validmin_mes);
         $this->pc23_validmin_ano = ($this->pc23_validmin_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc23_validmin_ano"]:$this->pc23_validmin_ano);
         if($this->pc23_validmin_dia != ""){
            $this->pc23_validmin = $this->pc23_validmin_ano."-".$this->pc23_validmin_mes."-".$this->pc23_validmin_dia;
         }
       }
     }else{
       $this->pc23_orcamforne = ($this->pc23_orcamforne == ""?@$GLOBALS["HTTP_POST_VARS"]["pc23_orcamforne"]:$this->pc23_orcamforne);
       $this->pc23_orcamitem = ($this->pc23_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc23_orcamitem"]:$this->pc23_orcamitem);
     }
   }
   // funcao para inclusao
   function incluir ($pc23_orcamforne,$pc23_orcamitem){ 
      $this->atualizacampos();
     if($this->pc23_valor == null ){ 
       $this->erro_sql = " Campo Valor or�ado nao Informado.";
       $this->erro_campo = "pc23_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc23_quant == null ){ 
       $this->erro_sql = " Campo Quantidade or�ada nao Informado.";
       $this->erro_campo = "pc23_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc23_vlrun == null ){ 
       $this->erro_sql = " Campo Valor unit�rio nao Informado.";
       $this->erro_campo = "pc23_vlrun";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc23_validmin == null ){ 
       $this->pc23_validmin = "null";
     }
       $this->pc23_orcamforne = $pc23_orcamforne; 
       $this->pc23_orcamitem = $pc23_orcamitem; 
     if(($this->pc23_orcamforne == null) || ($this->pc23_orcamforne == "") ){ 
       $this->erro_sql = " Campo pc23_orcamforne nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->pc23_orcamitem == null) || ($this->pc23_orcamitem == "") ){ 
       $this->erro_sql = " Campo pc23_orcamitem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcorcamval(
                                       pc23_orcamforne 
                                      ,pc23_orcamitem 
                                      ,pc23_valor 
                                      ,pc23_quant 
                                      ,pc23_obs 
                                      ,pc23_vlrun 
                                      ,pc23_validmin 
                       )
                values (
                                $this->pc23_orcamforne 
                               ,$this->pc23_orcamitem 
                               ,$this->pc23_valor 
                               ,$this->pc23_quant 
                               ,'$this->pc23_obs' 
                               ,$this->pc23_vlrun 
                               ,".($this->pc23_validmin == "null" || $this->pc23_validmin == ""?"null":"'".$this->pc23_validmin."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores dos itens do or�amento ($this->pc23_orcamforne."-".$this->pc23_orcamitem) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores dos itens do or�amento j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores dos itens do or�amento ($this->pc23_orcamforne."-".$this->pc23_orcamitem) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc23_orcamforne."-".$this->pc23_orcamitem;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc23_orcamforne,$this->pc23_orcamitem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6378,'$this->pc23_orcamforne','I')");
       $resac = db_query("insert into db_acountkey values($acount,5517,'$this->pc23_orcamitem','I')");
       $resac = db_query("insert into db_acount values($acount,863,6378,'','".AddSlashes(pg_result($resaco,0,'pc23_orcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,863,5517,'','".AddSlashes(pg_result($resaco,0,'pc23_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,863,5518,'','".AddSlashes(pg_result($resaco,0,'pc23_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,863,6456,'','".AddSlashes(pg_result($resaco,0,'pc23_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,863,6831,'','".AddSlashes(pg_result($resaco,0,'pc23_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,863,7645,'','".AddSlashes(pg_result($resaco,0,'pc23_vlrun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,863,9205,'','".AddSlashes(pg_result($resaco,0,'pc23_validmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc23_orcamforne=null,$pc23_orcamitem=null) { 
      $this->atualizacampos();
     $sql = " update pcorcamval set ";
     $virgula = "";
     if(trim($this->pc23_orcamforne)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc23_orcamforne"])){ 
       $sql  .= $virgula." pc23_orcamforne = $this->pc23_orcamforne ";
       $virgula = ",";
       if(trim($this->pc23_orcamforne) == null ){ 
         $this->erro_sql = " Campo C�digo do orcamento deste fornecedor nao Informado.";
         $this->erro_campo = "pc23_orcamforne";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc23_orcamitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc23_orcamitem"])){ 
       $sql  .= $virgula." pc23_orcamitem = $this->pc23_orcamitem ";
       $virgula = ",";
       if(trim($this->pc23_orcamitem) == null ){ 
         $this->erro_sql = " Campo C�digo sequencial do item no or�amento nao Informado.";
         $this->erro_campo = "pc23_orcamitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc23_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc23_valor"])){ 
       $sql  .= $virgula." pc23_valor = $this->pc23_valor ";
       $virgula = ",";
       if(trim($this->pc23_valor) == null ){ 
         $this->erro_sql = " Campo Valor or�ado nao Informado.";
         $this->erro_campo = "pc23_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc23_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc23_quant"])){ 
       $sql  .= $virgula." pc23_quant = $this->pc23_quant ";
       $virgula = ",";
       if(trim($this->pc23_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade or�ada nao Informado.";
         $this->erro_campo = "pc23_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc23_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc23_obs"])){ 
       $sql  .= $virgula." pc23_obs = '$this->pc23_obs' ";
       $virgula = ",";
     }
     if(trim($this->pc23_vlrun)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc23_vlrun"])){ 
       $sql  .= $virgula." pc23_vlrun = $this->pc23_vlrun ";
       $virgula = ",";
       if(trim($this->pc23_vlrun) == null ){ 
         $this->erro_sql = " Campo Valor unit�rio nao Informado.";
         $this->erro_campo = "pc23_vlrun";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc23_validmin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc23_validmin_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc23_validmin_dia"] !="") ){ 
       $sql  .= $virgula." pc23_validmin = '$this->pc23_validmin' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc23_validmin_dia"])){ 
         $sql  .= $virgula." pc23_validmin = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($pc23_orcamforne!=null){
       $sql .= " pc23_orcamforne = $this->pc23_orcamforne";
     }
     if($pc23_orcamitem!=null){
       $sql .= " and  pc23_orcamitem = $this->pc23_orcamitem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc23_orcamforne,$this->pc23_orcamitem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6378,'$this->pc23_orcamforne','A')");
         $resac = db_query("insert into db_acountkey values($acount,5517,'$this->pc23_orcamitem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc23_orcamforne"]))
           $resac = db_query("insert into db_acount values($acount,863,6378,'".AddSlashes(pg_result($resaco,$conresaco,'pc23_orcamforne'))."','$this->pc23_orcamforne',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc23_orcamitem"]))
           $resac = db_query("insert into db_acount values($acount,863,5517,'".AddSlashes(pg_result($resaco,$conresaco,'pc23_orcamitem'))."','$this->pc23_orcamitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc23_valor"]))
           $resac = db_query("insert into db_acount values($acount,863,5518,'".AddSlashes(pg_result($resaco,$conresaco,'pc23_valor'))."','$this->pc23_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc23_quant"]))
           $resac = db_query("insert into db_acount values($acount,863,6456,'".AddSlashes(pg_result($resaco,$conresaco,'pc23_quant'))."','$this->pc23_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc23_obs"]))
           $resac = db_query("insert into db_acount values($acount,863,6831,'".AddSlashes(pg_result($resaco,$conresaco,'pc23_obs'))."','$this->pc23_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc23_vlrun"]))
           $resac = db_query("insert into db_acount values($acount,863,7645,'".AddSlashes(pg_result($resaco,$conresaco,'pc23_vlrun'))."','$this->pc23_vlrun',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc23_validmin"]))
           $resac = db_query("insert into db_acount values($acount,863,9205,'".AddSlashes(pg_result($resaco,$conresaco,'pc23_validmin'))."','$this->pc23_validmin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores dos itens do or�amento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc23_orcamforne."-".$this->pc23_orcamitem;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores dos itens do or�amento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc23_orcamforne."-".$this->pc23_orcamitem;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc23_orcamforne."-".$this->pc23_orcamitem;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc23_orcamforne=null,$pc23_orcamitem=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc23_orcamforne,$pc23_orcamitem));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6378,'$pc23_orcamforne','E')");
         $resac = db_query("insert into db_acountkey values($acount,5517,'$pc23_orcamitem','E')");
         $resac = db_query("insert into db_acount values($acount,863,6378,'','".AddSlashes(pg_result($resaco,$iresaco,'pc23_orcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,863,5517,'','".AddSlashes(pg_result($resaco,$iresaco,'pc23_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,863,5518,'','".AddSlashes(pg_result($resaco,$iresaco,'pc23_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,863,6456,'','".AddSlashes(pg_result($resaco,$iresaco,'pc23_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,863,6831,'','".AddSlashes(pg_result($resaco,$iresaco,'pc23_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,863,7645,'','".AddSlashes(pg_result($resaco,$iresaco,'pc23_vlrun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,863,9205,'','".AddSlashes(pg_result($resaco,$iresaco,'pc23_validmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcorcamval
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc23_orcamforne != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc23_orcamforne = $pc23_orcamforne ";
        }
        if($pc23_orcamitem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc23_orcamitem = $pc23_orcamitem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores dos itens do or�amento nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc23_orcamforne."-".$pc23_orcamitem;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores dos itens do or�amento nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc23_orcamforne."-".$pc23_orcamitem;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc23_orcamforne."-".$pc23_orcamitem;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcorcamval";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc23_orcamforne=null,$pc23_orcamitem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcorcamval ";
     $sql .= "      inner join pcorcamforne  on  pcorcamforne.pc21_orcamforne = pcorcamval.pc23_orcamforne";
     $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = pcorcamval.pc23_orcamitem";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcorcamforne.pc21_numcgm";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamforne.pc21_codorc";
     $sql .= "      inner join pcorcam a on  a.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql2 = "";
     if($dbwhere==""){
       if($pc23_orcamforne!=null ){
         $sql2 .= " where pcorcamval.pc23_orcamforne = $pc23_orcamforne "; 
       } 
       if($pc23_orcamitem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcorcamval.pc23_orcamitem = $pc23_orcamitem "; 
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
   function sql_query_file ( $pc23_orcamforne=null,$pc23_orcamitem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcorcamval ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc23_orcamforne!=null ){
         $sql2 .= " where pcorcamval.pc23_orcamforne = $pc23_orcamforne "; 
       } 
       if($pc23_orcamitem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcorcamval.pc23_orcamitem = $pc23_orcamitem "; 
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
   /**
   * retorna um sql com join para os tipos de empresas
   *
   * @param integer $pc23_orcamforne
   * @param integer $pc23_orcamitem
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */
function sql_query_fornec ( $pc23_orcamforne=null,$pc23_orcamitem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcorcamval ";
     $sql .= "      inner join pcorcamforne  on  pcorcamforne.pc21_orcamforne = pcorcamval.pc23_orcamforne";
     $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = pcorcamval.pc23_orcamitem";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcorcamforne.pc21_numcgm";
     $sql .= "      inner join pcorcam  on  pc20_codorc = pcorcamitem.pc22_codorc";
     $sql .= "      left join pcorcamjulg  on  pcorcamjulg.pc24_orcamitem = pcorcamval.pc23_orcamitem and pcorcamjulg.pc24_orcamforne=pcorcamval.pc23_orcamforne";
     $sql .= "      left  join pcorcamfornelic  on  pcorcamforne.pc21_orcamforne = pcorcamfornelic.pc31_orcamforne";
     $sql .= "      left join liclicitatipoempresa on pc31_liclicitatipoempresa = l32_sequencial"; 
     $sql2 = "";
     if($dbwhere==""){
       if($pc23_orcamforne!=null ){
         $sql2 .= " where pcorcamval.pc23_orcamforne = $pc23_orcamforne "; 
       } 
       if($pc23_orcamitem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcorcamval.pc23_orcamitem = $pc23_orcamitem "; 
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
   function sql_query_importa ( $pc23_orcamforne=null,$pc23_orcamitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamval ";
     $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = pcorcamval.pc23_orcamitem";
     $sql .= "      inner join pcorcamforne  on  pcorcamforne.pc21_orcamforne = pcorcamval.pc23_orcamforne";
     $sql .= "      inner join pcorcamitemsol  on  pcorcamitemsol.pc29_orcamitem = pcorcamitem.pc22_orcamitem";
     $sql2 = "";
     if($dbwhere==""){
       if($pc23_orcamforne!=null ){
         $sql2 .= " where pcorcamval.pc23_orcamforne = $pc23_orcamforne ";
       }
       if($pc23_orcamitem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " pcorcamval.pc23_orcamitem = $pc23_orcamitem ";
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
   function sql_query_julg ( $pc23_orcamforne=null,$pc23_orcamitem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcorcamval ";
     $sql .= "      inner join pcorcamforne  on  pcorcamforne.pc21_orcamforne = pcorcamval.pc23_orcamforne";
     $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = pcorcamval.pc23_orcamitem";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcorcamforne.pc21_numcgm";
     $sql .= "      inner join pcorcam  on  pc20_codorc = pcorcamitem.pc22_codorc";
     $sql .= "      left join pcorcamjulg  on  pcorcamjulg.pc24_orcamitem = pcorcamval.pc23_orcamitem and pcorcamjulg.pc24_orcamforne=pcorcamval.pc23_orcamforne";
     $sql2 = "";
     if($dbwhere==""){
       if($pc23_orcamforne!=null ){
         $sql2 .= " where pcorcamval.pc23_orcamforne = $pc23_orcamforne "; 
       } 
       if($pc23_orcamitem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcorcamval.pc23_orcamitem = $pc23_orcamitem "; 
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
  
 /**
   * retorna um sql com join para os tipos de empresas
   *
   * @param integer $pc23_orcamforne
   * @param integer $pc23_orcamitem
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */
  function sql_query_valor_rp( $pc23_orcamforne=null,$pc23_orcamitem=null,$campos="*",$ordem=null,$dbwhere="") { 

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
     $sql .= " from pcorcamval ";
     $sql .= "      inner join pcorcamforne  on  pcorcamforne.pc21_orcamforne = pcorcamval.pc23_orcamforne";
     $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = pcorcamval.pc23_orcamitem";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcorcamforne.pc21_numcgm";
     $sql .= "      inner join pcorcam  on  pc20_codorc = pcorcamitem.pc22_codorc";
     $sql .= "      inner join pcorcamitemproc on pcorcamitem.pc22_orcamitem   = pcorcamitemproc.pc31_orcamitem";
     $sql .= "      inner join pcprocitem      on pc31_pcprocitem = pc81_codprocitem"; 
     $sql2 = "";
     if($dbwhere==""){
       if($pc23_orcamforne!=null ){
         $sql2 .= " where pcorcamval.pc23_orcamforne = $pc23_orcamforne "; 
       } 
       if($pc23_orcamitem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcorcamval.pc23_orcamitem = $pc23_orcamitem "; 
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
}
?>