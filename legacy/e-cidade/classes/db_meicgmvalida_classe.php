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

//MODULO: ISSQN
//CLASSE DA ENTIDADE meicgmvalida
class cl_meicgmvalida { 
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
   var $q117_sequencial = 0; 
   var $q117_meicgm = 0; 
   var $q117_id_usuario = 0; 
   var $q117_hora = null; 
   var $q117_data_dia = null; 
   var $q117_data_mes = null; 
   var $q117_data_ano = null; 
   var $q117_data = null; 
   var $q117_obs = null; 
   var $q117_aprovado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q117_sequencial = int4 = Sequencial 
                 q117_meicgm = int4 = MEI 
                 q117_id_usuario = int4 = Usuário 
                 q117_hora = char(5) = Hora 
                 q117_data = date = Data 
                 q117_obs = text = Observação 
                 q117_aprovado = bool = Aprovado 
                 ";
   //funcao construtor da classe 
   function cl_meicgmvalida() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("meicgmvalida"); 
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
       $this->q117_sequencial = ($this->q117_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q117_sequencial"]:$this->q117_sequencial);
       $this->q117_meicgm = ($this->q117_meicgm == ""?@$GLOBALS["HTTP_POST_VARS"]["q117_meicgm"]:$this->q117_meicgm);
       $this->q117_id_usuario = ($this->q117_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["q117_id_usuario"]:$this->q117_id_usuario);
       $this->q117_hora = ($this->q117_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["q117_hora"]:$this->q117_hora);
       if($this->q117_data == ""){
         $this->q117_data_dia = ($this->q117_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q117_data_dia"]:$this->q117_data_dia);
         $this->q117_data_mes = ($this->q117_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q117_data_mes"]:$this->q117_data_mes);
         $this->q117_data_ano = ($this->q117_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q117_data_ano"]:$this->q117_data_ano);
         if($this->q117_data_dia != ""){
            $this->q117_data = $this->q117_data_ano."-".$this->q117_data_mes."-".$this->q117_data_dia;
         }
       }
       $this->q117_obs = ($this->q117_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["q117_obs"]:$this->q117_obs);
       $this->q117_aprovado = ($this->q117_aprovado == "f"?@$GLOBALS["HTTP_POST_VARS"]["q117_aprovado"]:$this->q117_aprovado);
     }else{
       $this->q117_sequencial = ($this->q117_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q117_sequencial"]:$this->q117_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q117_sequencial){ 
      $this->atualizacampos();
     if($this->q117_meicgm == null ){ 
       $this->erro_sql = " Campo MEI nao Informado.";
       $this->erro_campo = "q117_meicgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q117_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "q117_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q117_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "q117_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q117_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "q117_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q117_aprovado == null ){ 
       $this->erro_sql = " Campo Aprovado nao Informado.";
       $this->erro_campo = "q117_aprovado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q117_sequencial == "" || $q117_sequencial == null ){
       $result = db_query("select nextval('meicgmvalida_q117_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: meicgmvalida_q117_sequencial_seq do campo: q117_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q117_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from meicgmvalida_q117_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q117_sequencial)){
         $this->erro_sql = " Campo q117_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q117_sequencial = $q117_sequencial; 
       }
     }
     if(($this->q117_sequencial == null) || ($this->q117_sequencial == "") ){ 
       $this->erro_sql = " Campo q117_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into meicgmvalida(
                                       q117_sequencial 
                                      ,q117_meicgm 
                                      ,q117_id_usuario 
                                      ,q117_hora 
                                      ,q117_data 
                                      ,q117_obs 
                                      ,q117_aprovado 
                       )
                values (
                                $this->q117_sequencial 
                               ,$this->q117_meicgm 
                               ,$this->q117_id_usuario 
                               ,'$this->q117_hora' 
                               ,".($this->q117_data == "null" || $this->q117_data == ""?"null":"'".$this->q117_data."'")." 
                               ,'$this->q117_obs' 
                               ,'$this->q117_aprovado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Validação do MEI ($this->q117_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Validação do MEI já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Validação do MEI ($this->q117_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q117_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q117_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16652,'$this->q117_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2929,16652,'','".AddSlashes(pg_result($resaco,0,'q117_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2929,16653,'','".AddSlashes(pg_result($resaco,0,'q117_meicgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2929,16654,'','".AddSlashes(pg_result($resaco,0,'q117_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2929,16655,'','".AddSlashes(pg_result($resaco,0,'q117_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2929,16656,'','".AddSlashes(pg_result($resaco,0,'q117_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2929,16657,'','".AddSlashes(pg_result($resaco,0,'q117_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2929,16658,'','".AddSlashes(pg_result($resaco,0,'q117_aprovado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q117_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update meicgmvalida set ";
     $virgula = "";
     if(trim($this->q117_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q117_sequencial"])){ 
       $sql  .= $virgula." q117_sequencial = $this->q117_sequencial ";
       $virgula = ",";
       if(trim($this->q117_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q117_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q117_meicgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q117_meicgm"])){ 
       $sql  .= $virgula." q117_meicgm = $this->q117_meicgm ";
       $virgula = ",";
       if(trim($this->q117_meicgm) == null ){ 
         $this->erro_sql = " Campo MEI nao Informado.";
         $this->erro_campo = "q117_meicgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q117_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q117_id_usuario"])){ 
       $sql  .= $virgula." q117_id_usuario = $this->q117_id_usuario ";
       $virgula = ",";
       if(trim($this->q117_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "q117_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q117_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q117_hora"])){ 
       $sql  .= $virgula." q117_hora = '$this->q117_hora' ";
       $virgula = ",";
       if(trim($this->q117_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "q117_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q117_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q117_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q117_data_dia"] !="") ){ 
       $sql  .= $virgula." q117_data = '$this->q117_data' ";
       $virgula = ",";
       if(trim($this->q117_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "q117_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q117_data_dia"])){ 
         $sql  .= $virgula." q117_data = null ";
         $virgula = ",";
         if(trim($this->q117_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "q117_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q117_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q117_obs"])){ 
       $sql  .= $virgula." q117_obs = '$this->q117_obs' ";
       $virgula = ",";
     }
     if(trim($this->q117_aprovado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q117_aprovado"])){ 
       $sql  .= $virgula." q117_aprovado = '$this->q117_aprovado' ";
       $virgula = ",";
       if(trim($this->q117_aprovado) == null ){ 
         $this->erro_sql = " Campo Aprovado nao Informado.";
         $this->erro_campo = "q117_aprovado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q117_sequencial!=null){
       $sql .= " q117_sequencial = $this->q117_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q117_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16652,'$this->q117_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q117_sequencial"]) || $this->q117_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2929,16652,'".AddSlashes(pg_result($resaco,$conresaco,'q117_sequencial'))."','$this->q117_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q117_meicgm"]) || $this->q117_meicgm != "")
           $resac = db_query("insert into db_acount values($acount,2929,16653,'".AddSlashes(pg_result($resaco,$conresaco,'q117_meicgm'))."','$this->q117_meicgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q117_id_usuario"]) || $this->q117_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2929,16654,'".AddSlashes(pg_result($resaco,$conresaco,'q117_id_usuario'))."','$this->q117_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q117_hora"]) || $this->q117_hora != "")
           $resac = db_query("insert into db_acount values($acount,2929,16655,'".AddSlashes(pg_result($resaco,$conresaco,'q117_hora'))."','$this->q117_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q117_data"]) || $this->q117_data != "")
           $resac = db_query("insert into db_acount values($acount,2929,16656,'".AddSlashes(pg_result($resaco,$conresaco,'q117_data'))."','$this->q117_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q117_obs"]) || $this->q117_obs != "")
           $resac = db_query("insert into db_acount values($acount,2929,16657,'".AddSlashes(pg_result($resaco,$conresaco,'q117_obs'))."','$this->q117_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q117_aprovado"]) || $this->q117_aprovado != "")
           $resac = db_query("insert into db_acount values($acount,2929,16658,'".AddSlashes(pg_result($resaco,$conresaco,'q117_aprovado'))."','$this->q117_aprovado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Validação do MEI nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q117_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Validação do MEI nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q117_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q117_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16652,'$q117_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2929,16652,'','".AddSlashes(pg_result($resaco,$iresaco,'q117_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2929,16653,'','".AddSlashes(pg_result($resaco,$iresaco,'q117_meicgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2929,16654,'','".AddSlashes(pg_result($resaco,$iresaco,'q117_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2929,16655,'','".AddSlashes(pg_result($resaco,$iresaco,'q117_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2929,16656,'','".AddSlashes(pg_result($resaco,$iresaco,'q117_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2929,16657,'','".AddSlashes(pg_result($resaco,$iresaco,'q117_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2929,16658,'','".AddSlashes(pg_result($resaco,$iresaco,'q117_aprovado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from meicgmvalida
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q117_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q117_sequencial = $q117_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Validação do MEI nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q117_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Validação do MEI nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q117_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:meicgmvalida";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q117_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meicgmvalida ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = meicgmvalida.q117_id_usuario";
     $sql .= "      inner join meicgm  on  meicgm.q115_sequencial = meicgmvalida.q117_meicgm";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = meicgm.q115_numcgm";
     $sql .= "      inner join meisituacao  on  meisituacao.q116_sequencial = meicgm.q115_meisitucao";
     $sql2 = "";
     if($dbwhere==""){
       if($q117_sequencial!=null ){
         $sql2 .= " where meicgmvalida.q117_sequencial = $q117_sequencial "; 
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
   function sql_query_file ( $q117_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meicgmvalida ";
     $sql2 = "";
     if($dbwhere==""){
       if($q117_sequencial!=null ){
         $sql2 .= " where meicgmvalida.q117_sequencial = $q117_sequencial "; 
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