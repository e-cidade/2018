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

//MODULO: atendimento
//CLASSE DA ENTIDADE atendusucli
class cl_atendusucli { 
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
   var $at80_codatendcli = 0; 
   var $at80_id_usuario = 0; 
   var $at80_data_dia = null; 
   var $at80_data_mes = null; 
   var $at80_data_ano = null; 
   var $at80_data = null; 
   var $at80_hora = null; 
   var $at80_baixado = 'f'; 
   var $at80_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at80_codatendcli = int4 = Código do atendimento 
                 at80_id_usuario = int4 = Cod. Usuário 
                 at80_data = date = Data de criação 
                 at80_hora = varchar(5) = Hora de criação 
                 at80_baixado = bool = Baixado 
                 at80_obs = text = Observações 
                 ";
   //funcao construtor da classe 
   function cl_atendusucli() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atendusucli"); 
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
       $this->at80_codatendcli = ($this->at80_codatendcli == ""?@$GLOBALS["HTTP_POST_VARS"]["at80_codatendcli"]:$this->at80_codatendcli);
       $this->at80_id_usuario = ($this->at80_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["at80_id_usuario"]:$this->at80_id_usuario);
       if($this->at80_data == ""){
         $this->at80_data_dia = ($this->at80_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at80_data_dia"]:$this->at80_data_dia);
         $this->at80_data_mes = ($this->at80_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at80_data_mes"]:$this->at80_data_mes);
         $this->at80_data_ano = ($this->at80_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at80_data_ano"]:$this->at80_data_ano);
         if($this->at80_data_dia != ""){
            $this->at80_data = $this->at80_data_ano."-".$this->at80_data_mes."-".$this->at80_data_dia;
         }
       }
       $this->at80_hora = ($this->at80_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["at80_hora"]:$this->at80_hora);
       $this->at80_baixado = ($this->at80_baixado == "f"?@$GLOBALS["HTTP_POST_VARS"]["at80_baixado"]:$this->at80_baixado);
       $this->at80_obs = ($this->at80_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["at80_obs"]:$this->at80_obs);
     }else{
       $this->at80_codatendcli = ($this->at80_codatendcli == ""?@$GLOBALS["HTTP_POST_VARS"]["at80_codatendcli"]:$this->at80_codatendcli);
     }
   }
   // funcao para inclusao
   function incluir ($at80_codatendcli){ 
      $this->atualizacampos();
     if($this->at80_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "at80_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at80_data == null ){ 
       $this->erro_sql = " Campo Data de criação nao Informado.";
       $this->erro_campo = "at80_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at80_hora == null ){ 
       $this->erro_sql = " Campo Hora de criação nao Informado.";
       $this->erro_campo = "at80_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at80_baixado == null ){ 
       $this->erro_sql = " Campo Baixado nao Informado.";
       $this->erro_campo = "at80_baixado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at80_codatendcli == "" || $at80_codatendcli == null ){
       $result = @pg_query("select nextval('atendusucli_at80_codatendcli_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: atendusucli_at80_codatendcli_seq do campo: at80_codatendcli"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at80_codatendcli = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from atendusucli_at80_codatendcli_seq");
       if(($result != false) && (pg_result($result,0,0) < $at80_codatendcli)){
         $this->erro_sql = " Campo at80_codatendcli maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at80_codatendcli = $at80_codatendcli; 
       }
     }
     if(($this->at80_codatendcli == null) || ($this->at80_codatendcli == "") ){ 
       $this->erro_sql = " Campo at80_codatendcli nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atendusucli(
                                       at80_codatendcli 
                                      ,at80_id_usuario 
                                      ,at80_data 
                                      ,at80_hora 
                                      ,at80_baixado 
                                      ,at80_obs 
                       )
                values (
                                $this->at80_codatendcli 
                               ,$this->at80_id_usuario 
                               ,".($this->at80_data == "null" || $this->at80_data == ""?"null":"'".$this->at80_data."'")." 
                               ,'$this->at80_hora' 
                               ,'$this->at80_baixado' 
                               ,'$this->at80_obs' 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Atendimento de usuários das prefeituras ($this->at80_codatendcli) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Atendimento de usuários das prefeituras já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Atendimento de usuários das prefeituras ($this->at80_codatendcli) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at80_codatendcli;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at80_codatendcli));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,9188,'$this->at80_codatendcli','I')");
       $resac = pg_query("insert into db_acount values($acount,1572,9188,'','".AddSlashes(pg_result($resaco,0,'at80_codatendcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1572,9189,'','".AddSlashes(pg_result($resaco,0,'at80_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1572,9190,'','".AddSlashes(pg_result($resaco,0,'at80_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1572,9191,'','".AddSlashes(pg_result($resaco,0,'at80_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1572,9192,'','".AddSlashes(pg_result($resaco,0,'at80_baixado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1572,9193,'','".AddSlashes(pg_result($resaco,0,'at80_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at80_codatendcli=null) { 
      $this->atualizacampos();
     $sql = " update atendusucli set ";
     $virgula = "";
     if(trim($this->at80_codatendcli)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at80_codatendcli"])){ 
       $sql  .= $virgula." at80_codatendcli = $this->at80_codatendcli ";
       $virgula = ",";
       if(trim($this->at80_codatendcli) == null ){ 
         $this->erro_sql = " Campo Código do atendimento nao Informado.";
         $this->erro_campo = "at80_codatendcli";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at80_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at80_id_usuario"])){ 
       $sql  .= $virgula." at80_id_usuario = $this->at80_id_usuario ";
       $virgula = ",";
       if(trim($this->at80_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "at80_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at80_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at80_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at80_data_dia"] !="") ){ 
       $sql  .= $virgula." at80_data = '$this->at80_data' ";
       $virgula = ",";
       if(trim($this->at80_data) == null ){ 
         $this->erro_sql = " Campo Data de criação nao Informado.";
         $this->erro_campo = "at80_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at80_data_dia"])){ 
         $sql  .= $virgula." at80_data = null ";
         $virgula = ",";
         if(trim($this->at80_data) == null ){ 
           $this->erro_sql = " Campo Data de criação nao Informado.";
           $this->erro_campo = "at80_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at80_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at80_hora"])){ 
       $sql  .= $virgula." at80_hora = '$this->at80_hora' ";
       $virgula = ",";
       if(trim($this->at80_hora) == null ){ 
         $this->erro_sql = " Campo Hora de criação nao Informado.";
         $this->erro_campo = "at80_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at80_baixado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at80_baixado"])){ 
       $sql  .= $virgula." at80_baixado = '$this->at80_baixado' ";
       $virgula = ",";
       if(trim($this->at80_baixado) == null ){ 
         $this->erro_sql = " Campo Baixado nao Informado.";
         $this->erro_campo = "at80_baixado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at80_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at80_obs"])){ 
       $sql  .= $virgula." at80_obs = '$this->at80_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($at80_codatendcli!=null){
       $sql .= " at80_codatendcli = $this->at80_codatendcli";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at80_codatendcli));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,9188,'$this->at80_codatendcli','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at80_codatendcli"]))
           $resac = pg_query("insert into db_acount values($acount,1572,9188,'".AddSlashes(pg_result($resaco,$conresaco,'at80_codatendcli'))."','$this->at80_codatendcli',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at80_id_usuario"]))
           $resac = pg_query("insert into db_acount values($acount,1572,9189,'".AddSlashes(pg_result($resaco,$conresaco,'at80_id_usuario'))."','$this->at80_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at80_data"]))
           $resac = pg_query("insert into db_acount values($acount,1572,9190,'".AddSlashes(pg_result($resaco,$conresaco,'at80_data'))."','$this->at80_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at80_hora"]))
           $resac = pg_query("insert into db_acount values($acount,1572,9191,'".AddSlashes(pg_result($resaco,$conresaco,'at80_hora'))."','$this->at80_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at80_baixado"]))
           $resac = pg_query("insert into db_acount values($acount,1572,9192,'".AddSlashes(pg_result($resaco,$conresaco,'at80_baixado'))."','$this->at80_baixado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at80_obs"]))
           $resac = pg_query("insert into db_acount values($acount,1572,9193,'".AddSlashes(pg_result($resaco,$conresaco,'at80_obs'))."','$this->at80_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atendimento de usuários das prefeituras nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at80_codatendcli;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atendimento de usuários das prefeituras nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at80_codatendcli;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at80_codatendcli;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at80_codatendcli=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at80_codatendcli));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,9188,'$at80_codatendcli','E')");
         $resac = pg_query("insert into db_acount values($acount,1572,9188,'','".AddSlashes(pg_result($resaco,$iresaco,'at80_codatendcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1572,9189,'','".AddSlashes(pg_result($resaco,$iresaco,'at80_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1572,9190,'','".AddSlashes(pg_result($resaco,$iresaco,'at80_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1572,9191,'','".AddSlashes(pg_result($resaco,$iresaco,'at80_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1572,9192,'','".AddSlashes(pg_result($resaco,$iresaco,'at80_baixado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1572,9193,'','".AddSlashes(pg_result($resaco,$iresaco,'at80_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atendusucli
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at80_codatendcli != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at80_codatendcli = $at80_codatendcli ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atendimento de usuários das prefeituras nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at80_codatendcli;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atendimento de usuários das prefeituras nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at80_codatendcli;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at80_codatendcli;
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
        $this->erro_sql   = "Record Vazio na Tabela:atendusucli";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $at80_codatendcli=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendusucli ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = atendusucli.at80_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($at80_codatendcli!=null ){
         $sql2 .= " where atendusucli.at80_codatendcli = $at80_codatendcli "; 
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
   function sql_query_file ( $at80_codatendcli=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendusucli ";
     $sql2 = "";
     if($dbwhere==""){
       if($at80_codatendcli!=null ){
         $sql2 .= " where atendusucli.at80_codatendcli = $at80_codatendcli "; 
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