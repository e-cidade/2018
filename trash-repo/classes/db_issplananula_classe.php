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

//MODULO: issqn
//CLASSE DA ENTIDADE issplananula
class cl_issplananula { 
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
   var $q76_sequencial = 0; 
   var $q76_planilha = 0; 
   var $q76_data_dia = null; 
   var $q76_data_mes = null; 
   var $q76_data_ano = null; 
   var $q76_data = null; 
   var $q76_hora = null; 
   var $q76_motivo = null; 
   var $q76_ip = null; 
   var $q76_id_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q76_sequencial = int4 = Código 
                 q76_planilha = int4 = Planilha 
                 q76_data = date = Data da anulação 
                 q76_hora = char(5) = Hora da anulação 
                 q76_motivo = text = Motivo 
                 q76_ip = varchar(20) = IP 
                 q76_id_usuario = int4 = Usuario 
                 ";
   //funcao construtor da classe 
   function cl_issplananula() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issplananula"); 
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
       $this->q76_sequencial = ($this->q76_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q76_sequencial"]:$this->q76_sequencial);
       $this->q76_planilha = ($this->q76_planilha == ""?@$GLOBALS["HTTP_POST_VARS"]["q76_planilha"]:$this->q76_planilha);
       if($this->q76_data == ""){
         $this->q76_data_dia = ($this->q76_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q76_data_dia"]:$this->q76_data_dia);
         $this->q76_data_mes = ($this->q76_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q76_data_mes"]:$this->q76_data_mes);
         $this->q76_data_ano = ($this->q76_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q76_data_ano"]:$this->q76_data_ano);
         if($this->q76_data_dia != ""){
            $this->q76_data = $this->q76_data_ano."-".$this->q76_data_mes."-".$this->q76_data_dia;
         }
       }
       $this->q76_hora = ($this->q76_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["q76_hora"]:$this->q76_hora);
       $this->q76_motivo = ($this->q76_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["q76_motivo"]:$this->q76_motivo);
       $this->q76_ip = ($this->q76_ip == ""?@$GLOBALS["HTTP_POST_VARS"]["q76_ip"]:$this->q76_ip);
       $this->q76_id_usuario = ($this->q76_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["q76_id_usuario"]:$this->q76_id_usuario);
     }else{
       $this->q76_sequencial = ($this->q76_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q76_sequencial"]:$this->q76_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q76_sequencial){ 
      $this->atualizacampos();
     if($this->q76_planilha == null ){ 
       $this->erro_sql = " Campo Planilha nao Informado.";
       $this->erro_campo = "q76_planilha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q76_data == null ){ 
       $this->erro_sql = " Campo Data da anulação nao Informado.";
       $this->erro_campo = "q76_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q76_hora == null ){ 
       $this->erro_sql = " Campo Hora da anulação nao Informado.";
       $this->erro_campo = "q76_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q76_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "q76_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q76_ip == null ){ 
       $this->erro_sql = " Campo IP nao Informado.";
       $this->erro_campo = "q76_ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q76_id_usuario == null ){ 
       $this->q76_id_usuario = "0";
     }
     if($q76_sequencial == "" || $q76_sequencial == null ){
       $result = db_query("select nextval('issplananula_q76_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issplananula_q76_sequencial_seq do campo: q76_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q76_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issplananula_q76_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q76_sequencial)){
         $this->erro_sql = " Campo q76_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q76_sequencial = $q76_sequencial; 
       }
     }
     if(($this->q76_sequencial == null) || ($this->q76_sequencial == "") ){ 
       $this->erro_sql = " Campo q76_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issplananula(
                                       q76_sequencial 
                                      ,q76_planilha 
                                      ,q76_data 
                                      ,q76_hora 
                                      ,q76_motivo 
                                      ,q76_ip 
                                      ,q76_id_usuario 
                       )
                values (
                                $this->q76_sequencial 
                               ,$this->q76_planilha 
                               ,".($this->q76_data == "null" || $this->q76_data == ""?"null":"'".$this->q76_data."'")." 
                               ,'$this->q76_hora' 
                               ,'$this->q76_motivo' 
                               ,'$this->q76_ip' 
                               ,$this->q76_id_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "issplananula ($this->q76_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "issplananula já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "issplananula ($this->q76_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q76_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q76_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11987,'$this->q76_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2074,11987,'','".AddSlashes(pg_result($resaco,0,'q76_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2074,11988,'','".AddSlashes(pg_result($resaco,0,'q76_planilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2074,11989,'','".AddSlashes(pg_result($resaco,0,'q76_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2074,11990,'','".AddSlashes(pg_result($resaco,0,'q76_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2074,11991,'','".AddSlashes(pg_result($resaco,0,'q76_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2074,11993,'','".AddSlashes(pg_result($resaco,0,'q76_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2074,11992,'','".AddSlashes(pg_result($resaco,0,'q76_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q76_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issplananula set ";
     $virgula = "";
     if(trim($this->q76_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q76_sequencial"])){ 
       $sql  .= $virgula." q76_sequencial = $this->q76_sequencial ";
       $virgula = ",";
       if(trim($this->q76_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "q76_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q76_planilha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q76_planilha"])){ 
       $sql  .= $virgula." q76_planilha = $this->q76_planilha ";
       $virgula = ",";
       if(trim($this->q76_planilha) == null ){ 
         $this->erro_sql = " Campo Planilha nao Informado.";
         $this->erro_campo = "q76_planilha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q76_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q76_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q76_data_dia"] !="") ){ 
       $sql  .= $virgula." q76_data = '$this->q76_data' ";
       $virgula = ",";
       if(trim($this->q76_data) == null ){ 
         $this->erro_sql = " Campo Data da anulação nao Informado.";
         $this->erro_campo = "q76_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q76_data_dia"])){ 
         $sql  .= $virgula." q76_data = null ";
         $virgula = ",";
         if(trim($this->q76_data) == null ){ 
           $this->erro_sql = " Campo Data da anulação nao Informado.";
           $this->erro_campo = "q76_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q76_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q76_hora"])){ 
       $sql  .= $virgula." q76_hora = '$this->q76_hora' ";
       $virgula = ",";
       if(trim($this->q76_hora) == null ){ 
         $this->erro_sql = " Campo Hora da anulação nao Informado.";
         $this->erro_campo = "q76_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q76_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q76_motivo"])){ 
       $sql  .= $virgula." q76_motivo = '$this->q76_motivo' ";
       $virgula = ",";
       if(trim($this->q76_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "q76_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q76_ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q76_ip"])){ 
       $sql  .= $virgula." q76_ip = '$this->q76_ip' ";
       $virgula = ",";
       if(trim($this->q76_ip) == null ){ 
         $this->erro_sql = " Campo IP nao Informado.";
         $this->erro_campo = "q76_ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q76_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q76_id_usuario"])){ 
        if(trim($this->q76_id_usuario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q76_id_usuario"])){ 
           $this->q76_id_usuario = "0" ; 
        } 
       $sql  .= $virgula." q76_id_usuario = $this->q76_id_usuario ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q76_sequencial!=null){
       $sql .= " q76_sequencial = $this->q76_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q76_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11987,'$this->q76_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q76_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2074,11987,'".AddSlashes(pg_result($resaco,$conresaco,'q76_sequencial'))."','$this->q76_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q76_planilha"]))
           $resac = db_query("insert into db_acount values($acount,2074,11988,'".AddSlashes(pg_result($resaco,$conresaco,'q76_planilha'))."','$this->q76_planilha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q76_data"]))
           $resac = db_query("insert into db_acount values($acount,2074,11989,'".AddSlashes(pg_result($resaco,$conresaco,'q76_data'))."','$this->q76_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q76_hora"]))
           $resac = db_query("insert into db_acount values($acount,2074,11990,'".AddSlashes(pg_result($resaco,$conresaco,'q76_hora'))."','$this->q76_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q76_motivo"]))
           $resac = db_query("insert into db_acount values($acount,2074,11991,'".AddSlashes(pg_result($resaco,$conresaco,'q76_motivo'))."','$this->q76_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q76_ip"]))
           $resac = db_query("insert into db_acount values($acount,2074,11993,'".AddSlashes(pg_result($resaco,$conresaco,'q76_ip'))."','$this->q76_ip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q76_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,2074,11992,'".AddSlashes(pg_result($resaco,$conresaco,'q76_id_usuario'))."','$this->q76_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "issplananula nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q76_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "issplananula nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q76_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q76_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q76_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q76_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11987,'$q76_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2074,11987,'','".AddSlashes(pg_result($resaco,$iresaco,'q76_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2074,11988,'','".AddSlashes(pg_result($resaco,$iresaco,'q76_planilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2074,11989,'','".AddSlashes(pg_result($resaco,$iresaco,'q76_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2074,11990,'','".AddSlashes(pg_result($resaco,$iresaco,'q76_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2074,11991,'','".AddSlashes(pg_result($resaco,$iresaco,'q76_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2074,11993,'','".AddSlashes(pg_result($resaco,$iresaco,'q76_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2074,11992,'','".AddSlashes(pg_result($resaco,$iresaco,'q76_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issplananula
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q76_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q76_sequencial = $q76_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "issplananula nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q76_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "issplananula nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q76_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q76_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issplananula";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q76_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issplananula ";
     $sql .= "      inner join issplan  on  issplan.q20_planilha = issplananula.q76_planilha";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issplan.q20_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q76_sequencial!=null ){
         $sql2 .= " where issplananula.q76_sequencial = $q76_sequencial "; 
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
   function sql_query_file ( $q76_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issplananula ";
     $sql2 = "";
     if($dbwhere==""){
       if($q76_sequencial!=null ){
         $sql2 .= " where issplananula.q76_sequencial = $q76_sequencial "; 
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