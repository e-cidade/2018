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

//MODULO: juridico
//CLASSE DA ENTIDADE inicialmov
class cl_inicialmov { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $v56_codmov = 0; 
   var $v56_inicial = 0; 
   var $v56_codsit = 0; 
   var $v56_obs = null; 
   var $v56_data_dia = null; 
   var $v56_data_mes = null; 
   var $v56_data_ano = null; 
   var $v56_data = null; 
   var $v56_id_login = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v56_codmov = int4 = Movimento 
                 v56_inicial = int4 = Inicial Numero 
                 v56_codsit = int4 = Situação 
                 v56_obs = text = Observações 
                 v56_data = date = Data 
                 v56_id_login = int4 = Usuário 
                 ";
    //Atualizar Movimento da Inicial
    function atuinicialmov($inicial,$codsit){
      $clinicial = new cl_inicial;
      $v56_usuario=db_getsession("DB_id_usuario");
      $v56_data=date("Y-m-d",db_getsession("DB_datausu"));
      $this->v56_data=$v56_data;
      $this->v56_id_login=$v56_usuario;
      $this->v56_obs=(isset($this->v56_obs)?$this->v56_obs:"0");
      $this->v56_codsit=$codsit;
      $this->v56_inicial=$inicial;
      $this->incluir(0);
      $codmov=$this->v56_codmov;
      
      $clinicial->v50_inicial="";
      $clinicial->v50_advog="";
      $clinicial->v50_data="";
      $clinicial->v50_id_login="";
      $clinicial->v50_codlocal="";
      if(isset($HTTP_POST_VARS["v50_inicial"])){
         unset($HTTP_POST_VARS["v50_inicial"]);
      }  
      if(isset($HTTP_POST_VARS["v50_advog"])){
         unset($HTTP_POST_VARS["v50_advog"]);
      }  
      if(isset($HTTP_POST_VARS["v50_data"])){
         unset($HTTP_POST_VARS["v50_data"]);
      }  
      if(isset($HTTP_POST_VARS["v50_id_login"])){
         unset($HTTP_POST_VARS["v50_id_login"]);
      }  
      if(isset($HTTP_POST_VARS["v50_codlocal"])){
         unset($HTTP_POST_VARS["v50_codlocal"]);
      }  
      if(isset($GLOBALS["HTTP_POST_VARS"]["v50_data"])){
         unset($GLOBALS["HTTP_POST_VARS"]["v50_data"]);
      }  
	
       $clinicial->v50_codmov=$codmov;
       $clinicial->v50_inicial=$inicial;
       $clinicial->alterar($inicial);
       $this->erro_msg=$clinicial->erro_msg;
    
    }



		 
   //funcao construtor da classe 
   function cl_inicialmov() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("inicialmov"); 
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
       $this->v56_codmov = ($this->v56_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["v56_codmov"]:$this->v56_codmov);
       $this->v56_inicial = ($this->v56_inicial == ""?@$GLOBALS["HTTP_POST_VARS"]["v56_inicial"]:$this->v56_inicial);
       $this->v56_codsit = ($this->v56_codsit == ""?@$GLOBALS["HTTP_POST_VARS"]["v56_codsit"]:$this->v56_codsit);
       $this->v56_obs = ($this->v56_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["v56_obs"]:$this->v56_obs);
       if($this->v56_data == ""){
         $this->v56_data_dia = @$GLOBALS["HTTP_POST_VARS"]["v56_data_dia"];
         $this->v56_data_mes = @$GLOBALS["HTTP_POST_VARS"]["v56_data_mes"];
         $this->v56_data_ano = @$GLOBALS["HTTP_POST_VARS"]["v56_data_ano"];
         if($this->v56_data_dia != ""){
            $this->v56_data = $this->v56_data_ano."-".$this->v56_data_mes."-".$this->v56_data_dia;
         }
       }
       $this->v56_id_login = ($this->v56_id_login == ""?@$GLOBALS["HTTP_POST_VARS"]["v56_id_login"]:$this->v56_id_login);
     }else{
       $this->v56_codmov = ($this->v56_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["v56_codmov"]:$this->v56_codmov);
     }
   }
   // funcao para inclusao
   function incluir ($v56_codmov){ 
      $this->atualizacampos();
     if($this->v56_inicial == null ){ 
       $this->erro_sql = " Campo Inicial Numero nao Informado.";
       $this->erro_campo = "v56_inicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v56_codsit == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "v56_codsit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v56_obs == null ){ 
       $this->erro_sql = " Campo Observações nao Informado.";
       $this->erro_campo = "v56_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v56_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "v56_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v56_id_login == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "v56_id_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v56_codmov == "" || $v56_codmov == null ){
       $result = @pg_query("select nextval('inicialmov_v56_codmov_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: inicialmov_v56_codmov_seq do campo: v56_codmov"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v56_codmov = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from inicialmov_v56_codmov_seq");
       if(($result != false) && (pg_result($result,0,0) < $v56_codmov)){
         $this->erro_sql = " Campo v56_codmov maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v56_codmov = $v56_codmov; 
       }
     }
     if(($this->v56_codmov == null) || ($this->v56_codmov == "") ){ 
       $this->erro_sql = " Campo v56_codmov nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into inicialmov(
                                       v56_codmov 
                                      ,v56_inicial 
                                      ,v56_codsit 
                                      ,v56_obs 
                                      ,v56_data 
                                      ,v56_id_login 
                       )
                values (
                                $this->v56_codmov 
                               ,$this->v56_inicial 
                               ,$this->v56_codsit 
                               ,'$this->v56_obs' 
                               ,".($this->v56_data == "null" || $this->v56_data == ""?"null":"'".$this->v56_data."'")." 
                               ,$this->v56_id_login 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimentação da inicial ($this->v56_codmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimentação da inicial já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimentação da inicial ($this->v56_codmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v56_codmov;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->v56_codmov));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,2565,'$this->v56_codmov','I')");
       $resac = pg_query("insert into db_acount values($acount,420,2565,'','".pg_result($resaco,0,'v56_codmov')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,420,2571,'','".pg_result($resaco,0,'v56_inicial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,420,2572,'','".pg_result($resaco,0,'v56_codsit')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,420,2573,'','".pg_result($resaco,0,'v56_obs')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,420,1866,'','".pg_result($resaco,0,'v56_data')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,420,2575,'','".pg_result($resaco,0,'v56_id_login')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v56_codmov=null) { 
      $this->atualizacampos();
     $sql = " update inicialmov set ";
     $virgula = "";
     if($this->v56_codmov!="" || isset($GLOBALS["HTTP_POST_VARS"]["v56_codmov"])){ 
       $sql  .= $virgula." v56_codmov = $this->v56_codmov ";
       $virgula = ",";
       if($this->v56_codmov == null ){ 
         $this->erro_sql = " Campo Movimento nao Informado.";
         $this->erro_campo = "v56_codmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if($this->v56_inicial!="" || isset($GLOBALS["HTTP_POST_VARS"]["v56_inicial"])){ 
       $sql  .= $virgula." v56_inicial = $this->v56_inicial ";
       $virgula = ",";
       if($this->v56_inicial == null ){ 
         $this->erro_sql = " Campo Inicial Numero nao Informado.";
         $this->erro_campo = "v56_inicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if($this->v56_codsit!="" || isset($GLOBALS["HTTP_POST_VARS"]["v56_codsit"])){ 
       $sql  .= $virgula." v56_codsit = $this->v56_codsit ";
       $virgula = ",";
       if($this->v56_codsit == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "v56_codsit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if($this->v56_obs!="" || isset($GLOBALS["HTTP_POST_VARS"]["v56_obs"])){ 
       $sql  .= $virgula." v56_obs = '$this->v56_obs' ";
       $virgula = ",";
       if($this->v56_obs == null ){ 
         $this->erro_sql = " Campo Observações nao Informado.";
         $this->erro_campo = "v56_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if($this->v56_data!="" || isset($GLOBALS["HTTP_POST_VARS"]["v56_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v56_data_dia"] !="") ){ 
       $sql  .= $virgula." v56_data = '$this->v56_data' ";
       $virgula = ",";
       if($this->v56_data == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "v56_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if($this->v56_data!="" || isset($GLOBALS["HTTP_POST_VARS"]["v56_data"])){ 
         $sql  .= $virgula." v56_data = null ";
         $virgula = ",";
         if($this->v56_data == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "v56_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if($this->v56_id_login!="" || isset($GLOBALS["HTTP_POST_VARS"]["v56_id_login"])){ 
       $sql  .= $virgula." v56_id_login = $this->v56_id_login ";
       $virgula = ",";
       if($this->v56_id_login == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "v56_id_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  v56_codmov = $this->v56_codmov
";
     $resaco = $this->sql_record($this->sql_query_file($this->v56_codmov));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,2565,'$this->v56_codmov','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["v56_codmov"]))
         $resac = pg_query("insert into db_acount values($acount,420,2565,'".pg_result($resaco,0,'v56_codmov')."','$this->v56_codmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["v56_inicial"]))
         $resac = pg_query("insert into db_acount values($acount,420,2571,'".pg_result($resaco,0,'v56_inicial')."','$this->v56_inicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["v56_codsit"]))
         $resac = pg_query("insert into db_acount values($acount,420,2572,'".pg_result($resaco,0,'v56_codsit')."','$this->v56_codsit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["v56_obs"]))
         $resac = pg_query("insert into db_acount values($acount,420,2573,'".pg_result($resaco,0,'v56_obs')."','$this->v56_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["v56_data"]))
         $resac = pg_query("insert into db_acount values($acount,420,1866,'".pg_result($resaco,0,'v56_data')."','$this->v56_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["v56_id_login"]))
         $resac = pg_query("insert into db_acount values($acount,420,2575,'".pg_result($resaco,0,'v56_id_login')."','$this->v56_id_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentação da inicial nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v56_codmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentação da inicial nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v56_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v56_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v56_codmov=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->v56_codmov));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,2565,'".pg_result($resaco,$iresaco,'v56_codmov')."','E')");
       $resac = pg_query("insert into db_acount values($acount,420,2565,'','".pg_result($resaco,0,'v56_codmov')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,420,2571,'','".pg_result($resaco,0,'v56_inicial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,420,2572,'','".pg_result($resaco,0,'v56_codsit')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,420,2573,'','".pg_result($resaco,0,'v56_obs')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,420,1866,'','".pg_result($resaco,0,'v56_data')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,420,2575,'','".pg_result($resaco,0,'v56_id_login')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $sql = " delete from inicialmov
                    where ";
     $sql2 = "";
      if($this->v56_codmov != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " v56_codmov = $this->v56_codmov ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentação da inicial nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->v56_codmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentação da inicial nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->v56_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v56_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v56_codmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inicialmov ";
     $sql .= "      inner join inicial  on  inicial.v50_inicial = inicialmov.v56_inicial";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inicialmov.v56_id_login";
     $sql .= "      inner join situacao  on  situacao.v52_codsit = inicialmov.v56_codsit";
     $sql .= "      inner join advog  on  advog.v57_numcgm = inicial.v50_advog";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = inicial.v50_id_login";
     $sql .= "      inner join localiza  on  localiza.v54_codlocal = inicial.v50_codlocal";
     $sql2 = "";
     if($dbwhere==""){
       if($v56_codmov!=null ){
         $sql2 .= " where inicialmov.v56_codmov = $v56_codmov "; 
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
   function sql_query_file ( $v56_codmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inicialmov ";
     $sql2 = "";
     if($dbwhere==""){
       if($v56_codmov!=null ){
         $sql2 .= " where inicialmov.v56_codmov = $v56_codmov "; 
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