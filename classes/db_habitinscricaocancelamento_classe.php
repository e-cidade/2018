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

//MODULO: habitacao
//CLASSE DA ENTIDADE habitinscricaocancelamento
class cl_habitinscricaocancelamento { 
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
   var $ht22_sequencial = 0; 
   var $ht22_habitinscricao = 0; 
   var $ht22_id_usuario = 0; 
   var $ht22_data_dia = null; 
   var $ht22_data_mes = null; 
   var $ht22_data_ano = null; 
   var $ht22_data = null; 
   var $ht22_hora = null; 
   var $ht22_tipo = 0; 
   var $ht22_motivo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht22_sequencial = int4 = Código Sequencial 
                 ht22_habitinscricao = int4 = Inscrição 
                 ht22_id_usuario = int4 = Usuário 
                 ht22_data = date = Data 
                 ht22_hora = char(5) = Hora 
                 ht22_tipo = int4 = Tipo de Cancelamento 
                 ht22_motivo = text = Motivo 
                 ";
   //funcao construtor da classe 
   function cl_habitinscricaocancelamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habitinscricaocancelamento"); 
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
       $this->ht22_sequencial = ($this->ht22_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht22_sequencial"]:$this->ht22_sequencial);
       $this->ht22_habitinscricao = ($this->ht22_habitinscricao == ""?@$GLOBALS["HTTP_POST_VARS"]["ht22_habitinscricao"]:$this->ht22_habitinscricao);
       $this->ht22_id_usuario = ($this->ht22_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ht22_id_usuario"]:$this->ht22_id_usuario);
       if($this->ht22_data == ""){
         $this->ht22_data_dia = ($this->ht22_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht22_data_dia"]:$this->ht22_data_dia);
         $this->ht22_data_mes = ($this->ht22_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ht22_data_mes"]:$this->ht22_data_mes);
         $this->ht22_data_ano = ($this->ht22_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ht22_data_ano"]:$this->ht22_data_ano);
         if($this->ht22_data_dia != ""){
            $this->ht22_data = $this->ht22_data_ano."-".$this->ht22_data_mes."-".$this->ht22_data_dia;
         }
       }
       $this->ht22_hora = ($this->ht22_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ht22_hora"]:$this->ht22_hora);
       $this->ht22_tipo = ($this->ht22_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ht22_tipo"]:$this->ht22_tipo);
       $this->ht22_motivo = ($this->ht22_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["ht22_motivo"]:$this->ht22_motivo);
     }else{
       $this->ht22_sequencial = ($this->ht22_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht22_sequencial"]:$this->ht22_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ht22_sequencial){ 
      $this->atualizacampos();
     if($this->ht22_habitinscricao == null ){ 
       $this->erro_sql = " Campo Inscrição nao Informado.";
       $this->erro_campo = "ht22_habitinscricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht22_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ht22_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht22_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ht22_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht22_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "ht22_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht22_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Cancelamento nao Informado.";
       $this->erro_campo = "ht22_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht22_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "ht22_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ht22_sequencial == "" || $ht22_sequencial == null ){
       $result = db_query("select nextval('habitinscricaodesistencia_ht22_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: habitinscricaodesistencia_ht22_sequencial_seq do campo: ht22_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ht22_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from habitinscricaodesistencia_ht22_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ht22_sequencial)){
         $this->erro_sql = " Campo ht22_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ht22_sequencial = $ht22_sequencial; 
       }
     }
     if(($this->ht22_sequencial == null) || ($this->ht22_sequencial == "") ){ 
       $this->erro_sql = " Campo ht22_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habitinscricaocancelamento(
                                       ht22_sequencial 
                                      ,ht22_habitinscricao 
                                      ,ht22_id_usuario 
                                      ,ht22_data 
                                      ,ht22_hora 
                                      ,ht22_tipo 
                                      ,ht22_motivo 
                       )
                values (
                                $this->ht22_sequencial 
                               ,$this->ht22_habitinscricao 
                               ,$this->ht22_id_usuario 
                               ,".($this->ht22_data == "null" || $this->ht22_data == ""?"null":"'".$this->ht22_data."'")." 
                               ,'$this->ht22_hora' 
                               ,$this->ht22_tipo 
                               ,'$this->ht22_motivo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cancelamento da Inscrição ($this->ht22_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cancelamento da Inscrição já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cancelamento da Inscrição ($this->ht22_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht22_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht22_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17828,'$this->ht22_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3147,17828,'','".AddSlashes(pg_result($resaco,0,'ht22_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3147,17829,'','".AddSlashes(pg_result($resaco,0,'ht22_habitinscricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3147,17830,'','".AddSlashes(pg_result($resaco,0,'ht22_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3147,17831,'','".AddSlashes(pg_result($resaco,0,'ht22_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3147,17832,'','".AddSlashes(pg_result($resaco,0,'ht22_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3147,17833,'','".AddSlashes(pg_result($resaco,0,'ht22_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3147,17834,'','".AddSlashes(pg_result($resaco,0,'ht22_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht22_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update habitinscricaocancelamento set ";
     $virgula = "";
     if(trim($this->ht22_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht22_sequencial"])){ 
       $sql  .= $virgula." ht22_sequencial = $this->ht22_sequencial ";
       $virgula = ",";
       if(trim($this->ht22_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "ht22_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht22_habitinscricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht22_habitinscricao"])){ 
       $sql  .= $virgula." ht22_habitinscricao = $this->ht22_habitinscricao ";
       $virgula = ",";
       if(trim($this->ht22_habitinscricao) == null ){ 
         $this->erro_sql = " Campo Inscrição nao Informado.";
         $this->erro_campo = "ht22_habitinscricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht22_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht22_id_usuario"])){ 
       $sql  .= $virgula." ht22_id_usuario = $this->ht22_id_usuario ";
       $virgula = ",";
       if(trim($this->ht22_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ht22_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht22_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht22_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ht22_data_dia"] !="") ){ 
       $sql  .= $virgula." ht22_data = '$this->ht22_data' ";
       $virgula = ",";
       if(trim($this->ht22_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ht22_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ht22_data_dia"])){ 
         $sql  .= $virgula." ht22_data = null ";
         $virgula = ",";
         if(trim($this->ht22_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ht22_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ht22_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht22_hora"])){ 
       $sql  .= $virgula." ht22_hora = '$this->ht22_hora' ";
       $virgula = ",";
       if(trim($this->ht22_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "ht22_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht22_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht22_tipo"])){ 
       $sql  .= $virgula." ht22_tipo = $this->ht22_tipo ";
       $virgula = ",";
       if(trim($this->ht22_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Cancelamento nao Informado.";
         $this->erro_campo = "ht22_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht22_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht22_motivo"])){ 
       $sql  .= $virgula." ht22_motivo = '$this->ht22_motivo' ";
       $virgula = ",";
       if(trim($this->ht22_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "ht22_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ht22_sequencial!=null){
       $sql .= " ht22_sequencial = $this->ht22_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht22_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17828,'$this->ht22_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht22_sequencial"]) || $this->ht22_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3147,17828,'".AddSlashes(pg_result($resaco,$conresaco,'ht22_sequencial'))."','$this->ht22_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht22_habitinscricao"]) || $this->ht22_habitinscricao != "")
           $resac = db_query("insert into db_acount values($acount,3147,17829,'".AddSlashes(pg_result($resaco,$conresaco,'ht22_habitinscricao'))."','$this->ht22_habitinscricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht22_id_usuario"]) || $this->ht22_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3147,17830,'".AddSlashes(pg_result($resaco,$conresaco,'ht22_id_usuario'))."','$this->ht22_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht22_data"]) || $this->ht22_data != "")
           $resac = db_query("insert into db_acount values($acount,3147,17831,'".AddSlashes(pg_result($resaco,$conresaco,'ht22_data'))."','$this->ht22_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht22_hora"]) || $this->ht22_hora != "")
           $resac = db_query("insert into db_acount values($acount,3147,17832,'".AddSlashes(pg_result($resaco,$conresaco,'ht22_hora'))."','$this->ht22_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht22_tipo"]) || $this->ht22_tipo != "")
           $resac = db_query("insert into db_acount values($acount,3147,17833,'".AddSlashes(pg_result($resaco,$conresaco,'ht22_tipo'))."','$this->ht22_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht22_motivo"]) || $this->ht22_motivo != "")
           $resac = db_query("insert into db_acount values($acount,3147,17834,'".AddSlashes(pg_result($resaco,$conresaco,'ht22_motivo'))."','$this->ht22_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cancelamento da Inscrição nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht22_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cancelamento da Inscrição nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht22_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht22_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht22_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht22_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17828,'$ht22_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3147,17828,'','".AddSlashes(pg_result($resaco,$iresaco,'ht22_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3147,17829,'','".AddSlashes(pg_result($resaco,$iresaco,'ht22_habitinscricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3147,17830,'','".AddSlashes(pg_result($resaco,$iresaco,'ht22_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3147,17831,'','".AddSlashes(pg_result($resaco,$iresaco,'ht22_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3147,17832,'','".AddSlashes(pg_result($resaco,$iresaco,'ht22_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3147,17833,'','".AddSlashes(pg_result($resaco,$iresaco,'ht22_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3147,17834,'','".AddSlashes(pg_result($resaco,$iresaco,'ht22_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habitinscricaocancelamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht22_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht22_sequencial = $ht22_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cancelamento da Inscrição nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht22_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cancelamento da Inscrição nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht22_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht22_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:habitinscricaocancelamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht22_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitinscricaocancelamento ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = habitinscricaocancelamento.ht22_id_usuario";
     $sql .= "      inner join habitinscricao  on  habitinscricao.ht15_sequencial = habitinscricaocancelamento.ht22_habitinscricao";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = habitinscricao.ht15_id_usuario";
     $sql .= "      inner join habitcandidatointeresseprograma  on  habitcandidatointeresseprograma.ht13_sequencial = habitinscricao.ht15_habitcandidatointeresseprograma";
     $sql2 = "";
     if($dbwhere==""){
       if($ht22_sequencial!=null ){
         $sql2 .= " where habitinscricaocancelamento.ht22_sequencial = $ht22_sequencial "; 
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
   function sql_query_file ( $ht22_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitinscricaocancelamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht22_sequencial!=null ){
         $sql2 .= " where habitinscricaocancelamento.ht22_sequencial = $ht22_sequencial "; 
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