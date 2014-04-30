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

//MODULO: Habitacao
//CLASSE DA ENTIDADE habitprogramalistacompraitem
class cl_habitprogramalistacompraitem { 
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
   var $ht18_sequencial = 0; 
   var $ht18_habitprogramalistacompra = 0; 
   var $ht18_matunid = 0; 
   var $ht18_pcmater = 0; 
   var $ht18_quantidade = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht18_sequencial = int4 = Sequencial 
                 ht18_habitprogramalistacompra = int4 = Lista de Compras 
                 ht18_matunid = int4 = Unidade de Material 
                 ht18_pcmater = int4 = Material 
                 ht18_quantidade = float4 = Quantidade 
                 ";
   //funcao construtor da classe 
   function cl_habitprogramalistacompraitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habitprogramalistacompraitem"); 
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
       $this->ht18_sequencial = ($this->ht18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht18_sequencial"]:$this->ht18_sequencial);
       $this->ht18_habitprogramalistacompra = ($this->ht18_habitprogramalistacompra == ""?@$GLOBALS["HTTP_POST_VARS"]["ht18_habitprogramalistacompra"]:$this->ht18_habitprogramalistacompra);
       $this->ht18_matunid = ($this->ht18_matunid == ""?@$GLOBALS["HTTP_POST_VARS"]["ht18_matunid"]:$this->ht18_matunid);
       $this->ht18_pcmater = ($this->ht18_pcmater == ""?@$GLOBALS["HTTP_POST_VARS"]["ht18_pcmater"]:$this->ht18_pcmater);
       $this->ht18_quantidade = ($this->ht18_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ht18_quantidade"]:$this->ht18_quantidade);
     }else{
       $this->ht18_sequencial = ($this->ht18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht18_sequencial"]:$this->ht18_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ht18_sequencial){ 
      $this->atualizacampos();
     if($this->ht18_habitprogramalistacompra == null ){ 
       $this->erro_sql = " Campo Lista de Compras nao Informado.";
       $this->erro_campo = "ht18_habitprogramalistacompra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht18_matunid == null ){ 
       $this->erro_sql = " Campo Unidade de Material nao Informado.";
       $this->erro_campo = "ht18_matunid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht18_pcmater == null ){ 
       $this->erro_sql = " Campo Material nao Informado.";
       $this->erro_campo = "ht18_pcmater";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht18_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "ht18_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ht18_sequencial == "" || $ht18_sequencial == null ){
       $result = db_query("select nextval('habitprogramalistacompraitem_ht18_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: habitprogramalistacompraitem_ht18_sequencial_seq do campo: ht18_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ht18_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from habitprogramalistacompraitem_ht18_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ht18_sequencial)){
         $this->erro_sql = " Campo ht18_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ht18_sequencial = $ht18_sequencial; 
       }
     }
     if(($this->ht18_sequencial == null) || ($this->ht18_sequencial == "") ){ 
       $this->erro_sql = " Campo ht18_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habitprogramalistacompraitem(
                                       ht18_sequencial 
                                      ,ht18_habitprogramalistacompra 
                                      ,ht18_matunid 
                                      ,ht18_pcmater 
                                      ,ht18_quantidade 
                       )
                values (
                                $this->ht18_sequencial 
                               ,$this->ht18_habitprogramalistacompra 
                               ,$this->ht18_matunid 
                               ,$this->ht18_pcmater 
                               ,$this->ht18_quantidade 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Item da Lista de Compras do Programa da Habitação ($this->ht18_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Item da Lista de Compras do Programa da Habitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Item da Lista de Compras do Programa da Habitação ($this->ht18_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht18_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht18_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17025,'$this->ht18_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3006,17025,'','".AddSlashes(pg_result($resaco,0,'ht18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3006,17028,'','".AddSlashes(pg_result($resaco,0,'ht18_habitprogramalistacompra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3006,17026,'','".AddSlashes(pg_result($resaco,0,'ht18_matunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3006,17027,'','".AddSlashes(pg_result($resaco,0,'ht18_pcmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3006,17029,'','".AddSlashes(pg_result($resaco,0,'ht18_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht18_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update habitprogramalistacompraitem set ";
     $virgula = "";
     if(trim($this->ht18_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht18_sequencial"])){ 
       $sql  .= $virgula." ht18_sequencial = $this->ht18_sequencial ";
       $virgula = ",";
       if(trim($this->ht18_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ht18_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht18_habitprogramalistacompra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht18_habitprogramalistacompra"])){ 
       $sql  .= $virgula." ht18_habitprogramalistacompra = $this->ht18_habitprogramalistacompra ";
       $virgula = ",";
       if(trim($this->ht18_habitprogramalistacompra) == null ){ 
         $this->erro_sql = " Campo Lista de Compras nao Informado.";
         $this->erro_campo = "ht18_habitprogramalistacompra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht18_matunid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht18_matunid"])){ 
       $sql  .= $virgula." ht18_matunid = $this->ht18_matunid ";
       $virgula = ",";
       if(trim($this->ht18_matunid) == null ){ 
         $this->erro_sql = " Campo Unidade de Material nao Informado.";
         $this->erro_campo = "ht18_matunid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht18_pcmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht18_pcmater"])){ 
       $sql  .= $virgula." ht18_pcmater = $this->ht18_pcmater ";
       $virgula = ",";
       if(trim($this->ht18_pcmater) == null ){ 
         $this->erro_sql = " Campo Material nao Informado.";
         $this->erro_campo = "ht18_pcmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht18_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht18_quantidade"])){ 
       $sql  .= $virgula." ht18_quantidade = $this->ht18_quantidade ";
       $virgula = ",";
       if(trim($this->ht18_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "ht18_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ht18_sequencial!=null){
       $sql .= " ht18_sequencial = $this->ht18_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht18_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17025,'$this->ht18_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht18_sequencial"]) || $this->ht18_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3006,17025,'".AddSlashes(pg_result($resaco,$conresaco,'ht18_sequencial'))."','$this->ht18_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht18_habitprogramalistacompra"]) || $this->ht18_habitprogramalistacompra != "")
           $resac = db_query("insert into db_acount values($acount,3006,17028,'".AddSlashes(pg_result($resaco,$conresaco,'ht18_habitprogramalistacompra'))."','$this->ht18_habitprogramalistacompra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht18_matunid"]) || $this->ht18_matunid != "")
           $resac = db_query("insert into db_acount values($acount,3006,17026,'".AddSlashes(pg_result($resaco,$conresaco,'ht18_matunid'))."','$this->ht18_matunid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht18_pcmater"]) || $this->ht18_pcmater != "")
           $resac = db_query("insert into db_acount values($acount,3006,17027,'".AddSlashes(pg_result($resaco,$conresaco,'ht18_pcmater'))."','$this->ht18_pcmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht18_quantidade"]) || $this->ht18_quantidade != "")
           $resac = db_query("insert into db_acount values($acount,3006,17029,'".AddSlashes(pg_result($resaco,$conresaco,'ht18_quantidade'))."','$this->ht18_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Item da Lista de Compras do Programa da Habitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht18_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Item da Lista de Compras do Programa da Habitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht18_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht18_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17025,'$ht18_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3006,17025,'','".AddSlashes(pg_result($resaco,$iresaco,'ht18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3006,17028,'','".AddSlashes(pg_result($resaco,$iresaco,'ht18_habitprogramalistacompra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3006,17026,'','".AddSlashes(pg_result($resaco,$iresaco,'ht18_matunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3006,17027,'','".AddSlashes(pg_result($resaco,$iresaco,'ht18_pcmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3006,17029,'','".AddSlashes(pg_result($resaco,$iresaco,'ht18_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habitprogramalistacompraitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht18_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht18_sequencial = $ht18_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Item da Lista de Compras do Programa da Habitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht18_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Item da Lista de Compras do Programa da Habitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht18_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:habitprogramalistacompraitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitprogramalistacompraitem ";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = habitprogramalistacompraitem.ht18_pcmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = habitprogramalistacompraitem.ht18_matunid";
     $sql .= "      inner join habitprogramalistacompra  on  habitprogramalistacompra.ht17_sequencial = habitprogramalistacompraitem.ht18_habitprogramalistacompra";
     $sql2 = "";
     if($dbwhere==""){
       if($ht18_sequencial!=null ){
         $sql2 .= " where habitprogramalistacompraitem.ht18_sequencial = $ht18_sequencial "; 
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
   // funcao do sql 
   function sql_query_file ( $ht18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitprogramalistacompraitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht18_sequencial!=null ){
         $sql2 .= " where habitprogramalistacompraitem.ht18_sequencial = $ht18_sequencial "; 
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