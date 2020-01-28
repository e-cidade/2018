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

//MODULO: Compras
//CLASSE DA ENTIDADE solicitaanulada
class cl_solicitaanulada { 
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
   var $pc67_sequencial = 0; 
   var $pc67_usuario = 0; 
   var $pc67_data_dia = null; 
   var $pc67_data_mes = null; 
   var $pc67_data_ano = null; 
   var $pc67_data = null; 
   var $pc67_hora = null; 
   var $pc67_solicita = 0; 
   var $pc67_motivo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc67_sequencial = int4 = Sequencial 
                 pc67_usuario = int4 = Usu�rio 
                 pc67_data = date = Data 
                 pc67_hora = char(5) = Hora 
                 pc67_solicita = int4 = C�digo Solicita��o 
                 pc67_motivo = text = Motivo 
                 ";
   //funcao construtor da classe 
   function cl_solicitaanulada() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("solicitaanulada"); 
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
       $this->pc67_sequencial = ($this->pc67_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc67_sequencial"]:$this->pc67_sequencial);
       $this->pc67_usuario = ($this->pc67_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["pc67_usuario"]:$this->pc67_usuario);
       if($this->pc67_data == ""){
         $this->pc67_data_dia = ($this->pc67_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc67_data_dia"]:$this->pc67_data_dia);
         $this->pc67_data_mes = ($this->pc67_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc67_data_mes"]:$this->pc67_data_mes);
         $this->pc67_data_ano = ($this->pc67_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc67_data_ano"]:$this->pc67_data_ano);
         if($this->pc67_data_dia != ""){
            $this->pc67_data = $this->pc67_data_ano."-".$this->pc67_data_mes."-".$this->pc67_data_dia;
         }
       }
       $this->pc67_hora = ($this->pc67_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["pc67_hora"]:$this->pc67_hora);
       $this->pc67_solicita = ($this->pc67_solicita == ""?@$GLOBALS["HTTP_POST_VARS"]["pc67_solicita"]:$this->pc67_solicita);
       $this->pc67_motivo = ($this->pc67_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc67_motivo"]:$this->pc67_motivo);
     }else{
       $this->pc67_sequencial = ($this->pc67_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc67_sequencial"]:$this->pc67_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc67_sequencial){ 
      $this->atualizacampos();
     if($this->pc67_usuario == null ){ 
       $this->erro_sql = " Campo Usu�rio nao Informado.";
       $this->erro_campo = "pc67_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc67_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "pc67_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc67_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "pc67_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc67_solicita == null ){ 
       $this->erro_sql = " Campo C�digo Solicita��o nao Informado.";
       $this->erro_campo = "pc67_solicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc67_sequencial == "" || $pc67_sequencial == null ){
       $result = db_query("select nextval('solicitaanulada_pc67_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: solicitaanulada_pc67_sequencial_seq do campo: pc67_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc67_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from solicitaanulada_pc67_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc67_sequencial)){
         $this->erro_sql = " Campo pc67_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc67_sequencial = $pc67_sequencial; 
       }
     }
     if(($this->pc67_sequencial == null) || ($this->pc67_sequencial == "") ){ 
       $this->erro_sql = " Campo pc67_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into solicitaanulada(
                                       pc67_sequencial 
                                      ,pc67_usuario 
                                      ,pc67_data 
                                      ,pc67_hora 
                                      ,pc67_solicita 
                                      ,pc67_motivo 
                       )
                values (
                                $this->pc67_sequencial 
                               ,$this->pc67_usuario 
                               ,".($this->pc67_data == "null" || $this->pc67_data == ""?"null":"'".$this->pc67_data."'")." 
                               ,'$this->pc67_hora' 
                               ,$this->pc67_solicita 
                               ,'$this->pc67_motivo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Solicitacao de Anula��o ($this->pc67_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Solicitacao de Anula��o j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Solicitacao de Anula��o ($this->pc67_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc67_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc67_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17093,'$this->pc67_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3020,17093,'','".AddSlashes(pg_result($resaco,0,'pc67_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3020,17094,'','".AddSlashes(pg_result($resaco,0,'pc67_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3020,17095,'','".AddSlashes(pg_result($resaco,0,'pc67_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3020,17096,'','".AddSlashes(pg_result($resaco,0,'pc67_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3020,17097,'','".AddSlashes(pg_result($resaco,0,'pc67_solicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3020,17098,'','".AddSlashes(pg_result($resaco,0,'pc67_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc67_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update solicitaanulada set ";
     $virgula = "";
     if(trim($this->pc67_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc67_sequencial"])){ 
       $sql  .= $virgula." pc67_sequencial = $this->pc67_sequencial ";
       $virgula = ",";
       if(trim($this->pc67_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "pc67_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc67_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc67_usuario"])){ 
       $sql  .= $virgula." pc67_usuario = $this->pc67_usuario ";
       $virgula = ",";
       if(trim($this->pc67_usuario) == null ){ 
         $this->erro_sql = " Campo Usu�rio nao Informado.";
         $this->erro_campo = "pc67_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc67_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc67_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc67_data_dia"] !="") ){ 
       $sql  .= $virgula." pc67_data = '$this->pc67_data' ";
       $virgula = ",";
       if(trim($this->pc67_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "pc67_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc67_data_dia"])){ 
         $sql  .= $virgula." pc67_data = null ";
         $virgula = ",";
         if(trim($this->pc67_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "pc67_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc67_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc67_hora"])){ 
       $sql  .= $virgula." pc67_hora = '$this->pc67_hora' ";
       $virgula = ",";
       if(trim($this->pc67_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "pc67_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc67_solicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc67_solicita"])){ 
       $sql  .= $virgula." pc67_solicita = $this->pc67_solicita ";
       $virgula = ",";
       if(trim($this->pc67_solicita) == null ){ 
         $this->erro_sql = " Campo C�digo Solicita��o nao Informado.";
         $this->erro_campo = "pc67_solicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc67_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc67_motivo"])){ 
       $sql  .= $virgula." pc67_motivo = '$this->pc67_motivo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($pc67_sequencial!=null){
       $sql .= " pc67_sequencial = $this->pc67_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc67_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17093,'$this->pc67_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc67_sequencial"]) || $this->pc67_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3020,17093,'".AddSlashes(pg_result($resaco,$conresaco,'pc67_sequencial'))."','$this->pc67_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc67_usuario"]) || $this->pc67_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3020,17094,'".AddSlashes(pg_result($resaco,$conresaco,'pc67_usuario'))."','$this->pc67_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc67_data"]) || $this->pc67_data != "")
           $resac = db_query("insert into db_acount values($acount,3020,17095,'".AddSlashes(pg_result($resaco,$conresaco,'pc67_data'))."','$this->pc67_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc67_hora"]) || $this->pc67_hora != "")
           $resac = db_query("insert into db_acount values($acount,3020,17096,'".AddSlashes(pg_result($resaco,$conresaco,'pc67_hora'))."','$this->pc67_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc67_solicita"]) || $this->pc67_solicita != "")
           $resac = db_query("insert into db_acount values($acount,3020,17097,'".AddSlashes(pg_result($resaco,$conresaco,'pc67_solicita'))."','$this->pc67_solicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc67_motivo"]) || $this->pc67_motivo != "")
           $resac = db_query("insert into db_acount values($acount,3020,17098,'".AddSlashes(pg_result($resaco,$conresaco,'pc67_motivo'))."','$this->pc67_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Solicitacao de Anula��o nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc67_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Solicitacao de Anula��o nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc67_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc67_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc67_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc67_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17093,'$pc67_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3020,17093,'','".AddSlashes(pg_result($resaco,$iresaco,'pc67_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3020,17094,'','".AddSlashes(pg_result($resaco,$iresaco,'pc67_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3020,17095,'','".AddSlashes(pg_result($resaco,$iresaco,'pc67_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3020,17096,'','".AddSlashes(pg_result($resaco,$iresaco,'pc67_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3020,17097,'','".AddSlashes(pg_result($resaco,$iresaco,'pc67_solicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3020,17098,'','".AddSlashes(pg_result($resaco,$iresaco,'pc67_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from solicitaanulada
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc67_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc67_sequencial = $pc67_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Solicitacao de Anula��o nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc67_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Solicitacao de Anula��o nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc67_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc67_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:solicitaanulada";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc67_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solicitaanulada ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = solicitaanulada.pc67_usuario";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitaanulada.pc67_solicita";
     $sql .= "      inner join db_config  on  db_config.codigo = solicita.pc10_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = solicita.pc10_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      inner join solicitacaotipo  on  solicitacaotipo.pc52_sequencial = solicita.pc10_solicitacaotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($pc67_sequencial!=null ){
         $sql2 .= " where solicitaanulada.pc67_sequencial = $pc67_sequencial "; 
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
   function sql_query_file ( $pc67_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solicitaanulada ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc67_sequencial!=null ){
         $sql2 .= " where solicitaanulada.pc67_sequencial = $pc67_sequencial "; 
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