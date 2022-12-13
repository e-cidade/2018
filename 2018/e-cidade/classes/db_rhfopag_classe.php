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
//CLASSE DA ENTIDADE rhfopag
class cl_rhfopag { 
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
   var $rh66_regist = 0; 
   var $rh66_pis = null; 
   var $rh66_valor = 0; 
   var $rh66_proces = 0; 
   var $rh66_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh66_regist = int4 = Cadastro de Servidores 
                 rh66_pis = varchar(11) = Nro PIS 
                 rh66_valor = float8 = Valor 
                 rh66_proces = int4 = Processado 
                 rh66_instit = int4 = Cod. Instituição 
                 ";
   //funcao construtor da classe 
   function cl_rhfopag() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhfopag"); 
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
       $this->rh66_regist = ($this->rh66_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh66_regist"]:$this->rh66_regist);
       $this->rh66_pis = ($this->rh66_pis == ""?@$GLOBALS["HTTP_POST_VARS"]["rh66_pis"]:$this->rh66_pis);
       $this->rh66_valor = ($this->rh66_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh66_valor"]:$this->rh66_valor);
       $this->rh66_proces = ($this->rh66_proces == ""?@$GLOBALS["HTTP_POST_VARS"]["rh66_proces"]:$this->rh66_proces);
       $this->rh66_instit = ($this->rh66_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh66_instit"]:$this->rh66_instit);
     }else{
       $this->rh66_regist = ($this->rh66_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh66_regist"]:$this->rh66_regist);
       $this->rh66_instit = ($this->rh66_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh66_instit"]:$this->rh66_instit);
     }
   }
   // funcao para inclusao
   function incluir ($rh66_regist,$rh66_instit){ 
      $this->atualizacampos();
     if($this->rh66_pis == null ){ 
       $this->erro_sql = " Campo Nro PIS nao Informado.";
       $this->erro_campo = "rh66_pis";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh66_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "rh66_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh66_proces == null ){ 
       $this->erro_sql = " Campo Processado nao Informado.";
       $this->erro_campo = "rh66_proces";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->rh66_regist = $rh66_regist; 
       $this->rh66_instit = $rh66_instit; 
     if(($this->rh66_regist == null) || ($this->rh66_regist == "") ){ 
       $this->erro_sql = " Campo rh66_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh66_instit == null) || ($this->rh66_instit == "") ){ 
       $this->erro_sql = " Campo rh66_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhfopag(
                                       rh66_regist 
                                      ,rh66_pis 
                                      ,rh66_valor 
                                      ,rh66_proces 
                                      ,rh66_instit 
                       )
                values (
                                $this->rh66_regist 
                               ,'$this->rh66_pis' 
                               ,$this->rh66_valor 
                               ,$this->rh66_proces 
                               ,$this->rh66_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cadastro dos funcionarios com PIS-PASEP ($this->rh66_regist."-".$this->rh66_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cadastro dos funcionarios com PIS-PASEP já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cadastro dos funcionarios com PIS-PASEP ($this->rh66_regist."-".$this->rh66_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh66_regist."-".$this->rh66_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh66_regist,$this->rh66_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10177,'$this->rh66_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,10179,'$this->rh66_instit','I')");
       $resac = db_query("insert into db_acount values($acount,1750,10177,'','".AddSlashes(pg_result($resaco,0,'rh66_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1750,10174,'','".AddSlashes(pg_result($resaco,0,'rh66_pis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1750,10175,'','".AddSlashes(pg_result($resaco,0,'rh66_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1750,10176,'','".AddSlashes(pg_result($resaco,0,'rh66_proces'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1750,10179,'','".AddSlashes(pg_result($resaco,0,'rh66_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh66_regist=null,$rh66_instit=null) { 
      $this->atualizacampos();
     $sql = " update rhfopag set ";
     $virgula = "";
     if(trim($this->rh66_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh66_regist"])){ 
       $sql  .= $virgula." rh66_regist = $this->rh66_regist ";
       $virgula = ",";
       if(trim($this->rh66_regist) == null ){ 
         $this->erro_sql = " Campo Cadastro de Servidores nao Informado.";
         $this->erro_campo = "rh66_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh66_pis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh66_pis"])){ 
       $sql  .= $virgula." rh66_pis = '$this->rh66_pis' ";
       $virgula = ",";
       if(trim($this->rh66_pis) == null ){ 
         $this->erro_sql = " Campo Nro PIS nao Informado.";
         $this->erro_campo = "rh66_pis";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh66_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh66_valor"])){ 
       $sql  .= $virgula." rh66_valor = $this->rh66_valor ";
       $virgula = ",";
       if(trim($this->rh66_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "rh66_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh66_proces)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh66_proces"])){ 
       $sql  .= $virgula." rh66_proces = $this->rh66_proces ";
       $virgula = ",";
       if(trim($this->rh66_proces) == null ){ 
         $this->erro_sql = " Campo Processado nao Informado.";
         $this->erro_campo = "rh66_proces";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh66_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh66_instit"])){ 
       $sql  .= $virgula." rh66_instit = $this->rh66_instit ";
       $virgula = ",";
       if(trim($this->rh66_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "rh66_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh66_regist!=null){
       $sql .= " rh66_regist = $this->rh66_regist";
     }
     if($rh66_instit!=null){
       $sql .= " and  rh66_instit = $this->rh66_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh66_regist,$this->rh66_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10177,'$this->rh66_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,10179,'$this->rh66_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh66_regist"]))
           $resac = db_query("insert into db_acount values($acount,1750,10177,'".AddSlashes(pg_result($resaco,$conresaco,'rh66_regist'))."','$this->rh66_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh66_pis"]))
           $resac = db_query("insert into db_acount values($acount,1750,10174,'".AddSlashes(pg_result($resaco,$conresaco,'rh66_pis'))."','$this->rh66_pis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh66_valor"]))
           $resac = db_query("insert into db_acount values($acount,1750,10175,'".AddSlashes(pg_result($resaco,$conresaco,'rh66_valor'))."','$this->rh66_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh66_proces"]))
           $resac = db_query("insert into db_acount values($acount,1750,10176,'".AddSlashes(pg_result($resaco,$conresaco,'rh66_proces'))."','$this->rh66_proces',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh66_instit"]))
           $resac = db_query("insert into db_acount values($acount,1750,10179,'".AddSlashes(pg_result($resaco,$conresaco,'rh66_instit'))."','$this->rh66_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastro dos funcionarios com PIS-PASEP nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh66_regist."-".$this->rh66_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro dos funcionarios com PIS-PASEP nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh66_regist."-".$this->rh66_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh66_regist."-".$this->rh66_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh66_regist=null,$rh66_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh66_regist,$rh66_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10177,'$rh66_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,10179,'$rh66_instit','E')");
         $resac = db_query("insert into db_acount values($acount,1750,10177,'','".AddSlashes(pg_result($resaco,$iresaco,'rh66_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1750,10174,'','".AddSlashes(pg_result($resaco,$iresaco,'rh66_pis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1750,10175,'','".AddSlashes(pg_result($resaco,$iresaco,'rh66_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1750,10176,'','".AddSlashes(pg_result($resaco,$iresaco,'rh66_proces'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1750,10179,'','".AddSlashes(pg_result($resaco,$iresaco,'rh66_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhfopag
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh66_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh66_regist = $rh66_regist ";
        }
        if($rh66_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh66_instit = $rh66_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastro dos funcionarios com PIS-PASEP nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh66_regist."-".$rh66_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro dos funcionarios com PIS-PASEP nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh66_regist."-".$rh66_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh66_regist."-".$rh66_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhfopag";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh66_regist=null,$rh66_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhfopag ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh66_regist!=null ){
         $sql2 .= " where rhfopag.rh66_regist = $rh66_regist "; 
       } 
       if($rh66_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhfopag.rh66_instit = $rh66_instit "; 
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
   function sql_query_file ( $rh66_regist=null,$rh66_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhfopag ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh66_regist!=null ){
         $sql2 .= " where rhfopag.rh66_regist = $rh66_regist "; 
       } 
       if($rh66_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhfopag.rh66_instit = $rh66_instit "; 
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