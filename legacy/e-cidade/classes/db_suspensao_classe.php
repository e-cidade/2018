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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE suspensao
class cl_suspensao { 
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
   var $ar18_sequencial = 0; 
   var $ar18_procjur = 0; 
   var $ar18_instit = 0; 
   var $ar18_usuario = 0; 
   var $ar18_data_dia = null; 
   var $ar18_data_mes = null; 
   var $ar18_data_ano = null; 
   var $ar18_data = null; 
   var $ar18_hora = null; 
   var $ar18_obs = null; 
   var $ar18_situacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ar18_sequencial = int4 = Código da suspensão 
                 ar18_procjur = int4 = Processo Jurídico 
                 ar18_instit = int4 = Cod. Instituição 
                 ar18_usuario = int4 = Cod. Usuário 
                 ar18_data = date = Data 
                 ar18_hora = char(5) = Hora 
                 ar18_obs = text = Observação 
                 ar18_situacao = int4 = Situação 
                 ";
   //funcao construtor da classe 
   function cl_suspensao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("suspensao"); 
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
       $this->ar18_sequencial = ($this->ar18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar18_sequencial"]:$this->ar18_sequencial);
       $this->ar18_procjur = ($this->ar18_procjur == ""?@$GLOBALS["HTTP_POST_VARS"]["ar18_procjur"]:$this->ar18_procjur);
       $this->ar18_instit = ($this->ar18_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["ar18_instit"]:$this->ar18_instit);
       $this->ar18_usuario = ($this->ar18_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ar18_usuario"]:$this->ar18_usuario);
       if($this->ar18_data == ""){
         $this->ar18_data_dia = ($this->ar18_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ar18_data_dia"]:$this->ar18_data_dia);
         $this->ar18_data_mes = ($this->ar18_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ar18_data_mes"]:$this->ar18_data_mes);
         $this->ar18_data_ano = ($this->ar18_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ar18_data_ano"]:$this->ar18_data_ano);
         if($this->ar18_data_dia != ""){
            $this->ar18_data = $this->ar18_data_ano."-".$this->ar18_data_mes."-".$this->ar18_data_dia;
         }
       }
       $this->ar18_hora = ($this->ar18_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ar18_hora"]:$this->ar18_hora);
       $this->ar18_obs = ($this->ar18_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ar18_obs"]:$this->ar18_obs);
       $this->ar18_situacao = ($this->ar18_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ar18_situacao"]:$this->ar18_situacao);
     }else{
       $this->ar18_sequencial = ($this->ar18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar18_sequencial"]:$this->ar18_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ar18_sequencial){ 
      $this->atualizacampos();
     if($this->ar18_procjur == null ){ 
       $this->erro_sql = " Campo Processo Jurídico nao Informado.";
       $this->erro_campo = "ar18_procjur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar18_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "ar18_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar18_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "ar18_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar18_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ar18_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar18_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "ar18_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar18_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "ar18_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar18_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "ar18_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ar18_sequencial == "" || $ar18_sequencial == null ){
       $result = db_query("select nextval('suspensao_ar18_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: suspensao_ar18_sequencial_seq do campo: ar18_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ar18_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from suspensao_ar18_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ar18_sequencial)){
         $this->erro_sql = " Campo ar18_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ar18_sequencial = $ar18_sequencial; 
       }
     }
     if(($this->ar18_sequencial == null) || ($this->ar18_sequencial == "") ){ 
       $this->erro_sql = " Campo ar18_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into suspensao(
                                       ar18_sequencial 
                                      ,ar18_procjur 
                                      ,ar18_instit 
                                      ,ar18_usuario 
                                      ,ar18_data 
                                      ,ar18_hora 
                                      ,ar18_obs 
                                      ,ar18_situacao 
                       )
                values (
                                $this->ar18_sequencial 
                               ,$this->ar18_procjur 
                               ,$this->ar18_instit 
                               ,$this->ar18_usuario 
                               ,".($this->ar18_data == "null" || $this->ar18_data == ""?"null":"'".$this->ar18_data."'")." 
                               ,'$this->ar18_hora' 
                               ,'$this->ar18_obs' 
                               ,$this->ar18_situacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Suspensão ($this->ar18_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Suspensão já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Suspensão ($this->ar18_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar18_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ar18_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12691,'$this->ar18_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2218,12691,'','".AddSlashes(pg_result($resaco,0,'ar18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2218,12692,'','".AddSlashes(pg_result($resaco,0,'ar18_procjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2218,12693,'','".AddSlashes(pg_result($resaco,0,'ar18_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2218,12694,'','".AddSlashes(pg_result($resaco,0,'ar18_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2218,12695,'','".AddSlashes(pg_result($resaco,0,'ar18_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2218,12696,'','".AddSlashes(pg_result($resaco,0,'ar18_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2218,12697,'','".AddSlashes(pg_result($resaco,0,'ar18_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2218,12698,'','".AddSlashes(pg_result($resaco,0,'ar18_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ar18_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update suspensao set ";
     $virgula = "";
     if(trim($this->ar18_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar18_sequencial"])){ 
       $sql  .= $virgula." ar18_sequencial = $this->ar18_sequencial ";
       $virgula = ",";
       if(trim($this->ar18_sequencial) == null ){ 
         $this->erro_sql = " Campo Código da suspensão nao Informado.";
         $this->erro_campo = "ar18_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar18_procjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar18_procjur"])){ 
       $sql  .= $virgula." ar18_procjur = $this->ar18_procjur ";
       $virgula = ",";
       if(trim($this->ar18_procjur) == null ){ 
         $this->erro_sql = " Campo Processo Jurídico nao Informado.";
         $this->erro_campo = "ar18_procjur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar18_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar18_instit"])){ 
       $sql  .= $virgula." ar18_instit = $this->ar18_instit ";
       $virgula = ",";
       if(trim($this->ar18_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "ar18_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar18_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar18_usuario"])){ 
       $sql  .= $virgula." ar18_usuario = $this->ar18_usuario ";
       $virgula = ",";
       if(trim($this->ar18_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "ar18_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar18_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar18_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ar18_data_dia"] !="") ){ 
       $sql  .= $virgula." ar18_data = '$this->ar18_data' ";
       $virgula = ",";
       if(trim($this->ar18_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ar18_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ar18_data_dia"])){ 
         $sql  .= $virgula." ar18_data = null ";
         $virgula = ",";
         if(trim($this->ar18_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ar18_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ar18_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar18_hora"])){ 
       $sql  .= $virgula." ar18_hora = '$this->ar18_hora' ";
       $virgula = ",";
       if(trim($this->ar18_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "ar18_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar18_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar18_obs"])){ 
       $sql  .= $virgula." ar18_obs = '$this->ar18_obs' ";
       $virgula = ",";
       if(trim($this->ar18_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "ar18_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar18_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar18_situacao"])){ 
       $sql  .= $virgula." ar18_situacao = $this->ar18_situacao ";
       $virgula = ",";
       if(trim($this->ar18_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "ar18_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ar18_sequencial!=null){
       $sql .= " ar18_sequencial = $this->ar18_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ar18_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12691,'$this->ar18_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar18_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2218,12691,'".AddSlashes(pg_result($resaco,$conresaco,'ar18_sequencial'))."','$this->ar18_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar18_procjur"]))
           $resac = db_query("insert into db_acount values($acount,2218,12692,'".AddSlashes(pg_result($resaco,$conresaco,'ar18_procjur'))."','$this->ar18_procjur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar18_instit"]))
           $resac = db_query("insert into db_acount values($acount,2218,12693,'".AddSlashes(pg_result($resaco,$conresaco,'ar18_instit'))."','$this->ar18_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar18_usuario"]))
           $resac = db_query("insert into db_acount values($acount,2218,12694,'".AddSlashes(pg_result($resaco,$conresaco,'ar18_usuario'))."','$this->ar18_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar18_data"]))
           $resac = db_query("insert into db_acount values($acount,2218,12695,'".AddSlashes(pg_result($resaco,$conresaco,'ar18_data'))."','$this->ar18_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar18_hora"]))
           $resac = db_query("insert into db_acount values($acount,2218,12696,'".AddSlashes(pg_result($resaco,$conresaco,'ar18_hora'))."','$this->ar18_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar18_obs"]))
           $resac = db_query("insert into db_acount values($acount,2218,12697,'".AddSlashes(pg_result($resaco,$conresaco,'ar18_obs'))."','$this->ar18_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar18_situacao"]))
           $resac = db_query("insert into db_acount values($acount,2218,12698,'".AddSlashes(pg_result($resaco,$conresaco,'ar18_situacao'))."','$this->ar18_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Suspensão nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar18_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Suspensão nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ar18_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ar18_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12691,'$ar18_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2218,12691,'','".AddSlashes(pg_result($resaco,$iresaco,'ar18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2218,12692,'','".AddSlashes(pg_result($resaco,$iresaco,'ar18_procjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2218,12693,'','".AddSlashes(pg_result($resaco,$iresaco,'ar18_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2218,12694,'','".AddSlashes(pg_result($resaco,$iresaco,'ar18_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2218,12695,'','".AddSlashes(pg_result($resaco,$iresaco,'ar18_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2218,12696,'','".AddSlashes(pg_result($resaco,$iresaco,'ar18_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2218,12697,'','".AddSlashes(pg_result($resaco,$iresaco,'ar18_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2218,12698,'','".AddSlashes(pg_result($resaco,$iresaco,'ar18_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from suspensao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ar18_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ar18_sequencial = $ar18_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Suspensão nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ar18_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Suspensão nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ar18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ar18_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:suspensao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ar18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from suspensao ";
     $sql .= "      inner join db_config    on  db_config.codigo 	   	   = suspensao.ar18_instit  ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario 	   = suspensao.ar18_usuario ";
     $sql .= "      inner join procjur  	on  procjur.v62_sequencial 	   = suspensao.ar18_procjur ";
     $sql .= "      inner join procjurtipo  on  procjurtipo.v66_sequencial = procjur.v62_procjurtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($ar18_sequencial!=null ){
         $sql2 .= " where suspensao.ar18_sequencial = $ar18_sequencial "; 
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
   function sql_query_file ( $ar18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from suspensao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ar18_sequencial!=null ){
         $sql2 .= " where suspensao.ar18_sequencial = $ar18_sequencial "; 
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
   
  function sql_query_deb ( $ar18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from suspensao ";
     $sql .= "      inner join arresusp on arresusp.k00_suspensao = suspensao.ar18_sequencial ";
     
     $sql2 = "";
     if($dbwhere==""){
       if($ar18_sequencial!=null ){
         $sql2 .= " where suspensao.ar18_sequencial = $ar18_sequencial "; 
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

  function sql_query_proc( $ar18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from suspensao ";
     $sql .= "      inner join db_config    	 on db_config.codigo 	   	    	 = suspensao.ar18_instit     ";
     $sql .= "      inner join db_usuarios  	 on db_usuarios.id_usuario 	    	 = suspensao.ar18_usuario    ";
     $sql .= "      inner join procjur  		 on procjur.v62_sequencial 	    	 = suspensao.ar18_procjur    ";
     $sql .= "      inner join procjurtipo  	 on procjurtipo.v66_sequencial  	 = procjur.v62_procjurtipo   ";
     $sql .= "      left  join procjurjudicial 	 on procjurjudicial.v63_procjur 	 = procjur.v62_sequencial    ";
     $sql .= "      left  join procjuradm	  	 on procjuradm.v64_procjur 	    	 = procjur.v62_sequencial 	 ";
     $sql .= "      left  join suspensaofinaliza on suspensaofinaliza.ar19_suspensao = suspensao.ar18_sequencial ";
     $sql2 = "";
     if($dbwhere==""){
       if($ar18_sequencial!=null ){
         $sql2 .= " where suspensao.ar18_sequencial = $ar18_sequencial "; 
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