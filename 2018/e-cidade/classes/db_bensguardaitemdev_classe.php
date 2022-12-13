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

//MODULO: patrim
//CLASSE DA ENTIDADE bensguardaitemdev
class cl_bensguardaitemdev { 
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
   var $t23_guardaitem = 0; 
   var $t23_situacao = 0; 
   var $t23_data_dia = null; 
   var $t23_data_mes = null; 
   var $t23_data_ano = null; 
   var $t23_data = null; 
   var $t23_obs = null; 
   var $t23_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t23_guardaitem = int4 = Código bensguardaitem 
                 t23_situacao = int8 = Código da situação 
                 t23_data = date = Data da Devolução 
                 t23_obs = text = Observação 
                 t23_usuario = int4 = Cod. Usuário 
                 ";
   //funcao construtor da classe 
   function cl_bensguardaitemdev() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bensguardaitemdev"); 
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
       $this->t23_guardaitem = ($this->t23_guardaitem == ""?@$GLOBALS["HTTP_POST_VARS"]["t23_guardaitem"]:$this->t23_guardaitem);
       $this->t23_situacao = ($this->t23_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["t23_situacao"]:$this->t23_situacao);
       if($this->t23_data == ""){
         $this->t23_data_dia = ($this->t23_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t23_data_dia"]:$this->t23_data_dia);
         $this->t23_data_mes = ($this->t23_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t23_data_mes"]:$this->t23_data_mes);
         $this->t23_data_ano = ($this->t23_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t23_data_ano"]:$this->t23_data_ano);
         if($this->t23_data_dia != ""){
            $this->t23_data = $this->t23_data_ano."-".$this->t23_data_mes."-".$this->t23_data_dia;
         }
       }
       $this->t23_obs = ($this->t23_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["t23_obs"]:$this->t23_obs);
       $this->t23_usuario = ($this->t23_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["t23_usuario"]:$this->t23_usuario);
     }else{
       $this->t23_guardaitem = ($this->t23_guardaitem == ""?@$GLOBALS["HTTP_POST_VARS"]["t23_guardaitem"]:$this->t23_guardaitem);
     }
   }
   // funcao para inclusao
   function incluir ($t23_guardaitem){ 
      $this->atualizacampos();
     if($this->t23_situacao == null ){ 
       $this->erro_sql = " Campo Código da situação nao Informado.";
       $this->erro_campo = "t23_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t23_data == null ){ 
       $this->erro_sql = " Campo Data da Devolução nao Informado.";
       $this->erro_campo = "t23_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t23_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "t23_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->t23_guardaitem = $t23_guardaitem; 
     if(($this->t23_guardaitem == null) || ($this->t23_guardaitem == "") ){ 
       $this->erro_sql = " Campo t23_guardaitem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bensguardaitemdev(
                                       t23_guardaitem 
                                      ,t23_situacao 
                                      ,t23_data 
                                      ,t23_obs 
                                      ,t23_usuario 
                       )
                values (
                                $this->t23_guardaitem 
                               ,$this->t23_situacao 
                               ,".($this->t23_data == "null" || $this->t23_data == ""?"null":"'".$this->t23_data."'")." 
                               ,'$this->t23_obs' 
                               ,$this->t23_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "bens devolvidos não estão mais sobre guarda ($this->t23_guardaitem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "bens devolvidos não estão mais sobre guarda já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "bens devolvidos não estão mais sobre guarda ($this->t23_guardaitem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t23_guardaitem;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t23_guardaitem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8965,'$this->t23_guardaitem','I')");
       $resac = db_query("insert into db_acount values($acount,1535,8965,'','".AddSlashes(pg_result($resaco,0,'t23_guardaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1535,8966,'','".AddSlashes(pg_result($resaco,0,'t23_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1535,8969,'','".AddSlashes(pg_result($resaco,0,'t23_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1535,8967,'','".AddSlashes(pg_result($resaco,0,'t23_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1535,8968,'','".AddSlashes(pg_result($resaco,0,'t23_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t23_guardaitem=null) { 
      $this->atualizacampos();
     $sql = " update bensguardaitemdev set ";
     $virgula = "";
     if(trim($this->t23_guardaitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t23_guardaitem"])){ 
       $sql  .= $virgula." t23_guardaitem = $this->t23_guardaitem ";
       $virgula = ",";
       if(trim($this->t23_guardaitem) == null ){ 
         $this->erro_sql = " Campo Código bensguardaitem nao Informado.";
         $this->erro_campo = "t23_guardaitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t23_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t23_situacao"])){ 
       $sql  .= $virgula." t23_situacao = $this->t23_situacao ";
       $virgula = ",";
       if(trim($this->t23_situacao) == null ){ 
         $this->erro_sql = " Campo Código da situação nao Informado.";
         $this->erro_campo = "t23_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t23_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t23_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t23_data_dia"] !="") ){ 
       $sql  .= $virgula." t23_data = '$this->t23_data' ";
       $virgula = ",";
       if(trim($this->t23_data) == null ){ 
         $this->erro_sql = " Campo Data da Devolução nao Informado.";
         $this->erro_campo = "t23_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t23_data_dia"])){ 
         $sql  .= $virgula." t23_data = null ";
         $virgula = ",";
         if(trim($this->t23_data) == null ){ 
           $this->erro_sql = " Campo Data da Devolução nao Informado.";
           $this->erro_campo = "t23_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t23_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t23_obs"])){ 
       $sql  .= $virgula." t23_obs = '$this->t23_obs' ";
       $virgula = ",";
     }
     if(trim($this->t23_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t23_usuario"])){ 
       $sql  .= $virgula." t23_usuario = $this->t23_usuario ";
       $virgula = ",";
       if(trim($this->t23_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "t23_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t23_guardaitem!=null){
       $sql .= " t23_guardaitem = $this->t23_guardaitem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t23_guardaitem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8965,'$this->t23_guardaitem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t23_guardaitem"]))
           $resac = db_query("insert into db_acount values($acount,1535,8965,'".AddSlashes(pg_result($resaco,$conresaco,'t23_guardaitem'))."','$this->t23_guardaitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t23_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1535,8966,'".AddSlashes(pg_result($resaco,$conresaco,'t23_situacao'))."','$this->t23_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t23_data"]))
           $resac = db_query("insert into db_acount values($acount,1535,8969,'".AddSlashes(pg_result($resaco,$conresaco,'t23_data'))."','$this->t23_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t23_obs"]))
           $resac = db_query("insert into db_acount values($acount,1535,8967,'".AddSlashes(pg_result($resaco,$conresaco,'t23_obs'))."','$this->t23_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t23_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1535,8968,'".AddSlashes(pg_result($resaco,$conresaco,'t23_usuario'))."','$this->t23_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "bens devolvidos não estão mais sobre guarda nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t23_guardaitem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "bens devolvidos não estão mais sobre guarda nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t23_guardaitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t23_guardaitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t23_guardaitem=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t23_guardaitem));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8965,'$t23_guardaitem','E')");
         $resac = db_query("insert into db_acount values($acount,1535,8965,'','".AddSlashes(pg_result($resaco,$iresaco,'t23_guardaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1535,8966,'','".AddSlashes(pg_result($resaco,$iresaco,'t23_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1535,8969,'','".AddSlashes(pg_result($resaco,$iresaco,'t23_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1535,8967,'','".AddSlashes(pg_result($resaco,$iresaco,'t23_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1535,8968,'','".AddSlashes(pg_result($resaco,$iresaco,'t23_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from bensguardaitemdev
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t23_guardaitem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t23_guardaitem = $t23_guardaitem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "bens devolvidos não estão mais sobre guarda nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t23_guardaitem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "bens devolvidos não estão mais sobre guarda nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t23_guardaitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t23_guardaitem;
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
        $this->erro_sql   = "Record Vazio na Tabela:bensguardaitemdev";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $t23_guardaitem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensguardaitemdev ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = bensguardaitemdev.t23_usuario";
     $sql .= "      inner join situabens  on  situabens.t70_situac = bensguardaitemdev.t23_situacao";
     $sql .= "      inner join bensguardaitem  on  bensguardaitem.t22_codigo = bensguardaitemdev.t23_guardaitem";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = bensguardaitem.t22_usuario";
     $sql .= "      inner join bens  as a on   a.t52_bem = bensguardaitem.t22_bem";
     $sql .= "      inner join bensguarda  as b on   b.t21_codigo = bensguardaitem.t22_bensguarda";
     $sql2 = "";
     if($dbwhere==""){
       if($t23_guardaitem!=null ){
         $sql2 .= " where bensguardaitemdev.t23_guardaitem = $t23_guardaitem "; 
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
   function sql_query_file ( $t23_guardaitem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensguardaitemdev ";
     $sql2 = "";
     if($dbwhere==""){
       if($t23_guardaitem!=null ){
         $sql2 .= " where bensguardaitemdev.t23_guardaitem = $t23_guardaitem "; 
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