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

//MODULO: Vacinas
//CLASSE DA ENTIDADE vac_aplicaanula
class cl_vac_aplicaanula { 
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
   var $vc18_i_codigo = 0; 
   var $vc18_i_aplica = 0; 
   var $vc18_i_usuario = 0; 
   var $vc18_c_hora = null; 
   var $vc18_d_data_dia = null; 
   var $vc18_d_data_mes = null; 
   var $vc18_d_data_ano = null; 
   var $vc18_d_data = null; 
   var $vc18_t_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 vc18_i_codigo = int4 = Código 
                 vc18_i_aplica = int4 = Aplicação 
                 vc18_i_usuario = int4 = Usuário 
                 vc18_c_hora = char(5) = Hora 
                 vc18_d_data = date = Data 
                 vc18_t_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_vac_aplicaanula() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vac_aplicaanula"); 
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
       $this->vc18_i_codigo = ($this->vc18_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc18_i_codigo"]:$this->vc18_i_codigo);
       $this->vc18_i_aplica = ($this->vc18_i_aplica == ""?@$GLOBALS["HTTP_POST_VARS"]["vc18_i_aplica"]:$this->vc18_i_aplica);
       $this->vc18_i_usuario = ($this->vc18_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["vc18_i_usuario"]:$this->vc18_i_usuario);
       $this->vc18_c_hora = ($this->vc18_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["vc18_c_hora"]:$this->vc18_c_hora);
       if($this->vc18_d_data == ""){
         $this->vc18_d_data_dia = ($this->vc18_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["vc18_d_data_dia"]:$this->vc18_d_data_dia);
         $this->vc18_d_data_mes = ($this->vc18_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["vc18_d_data_mes"]:$this->vc18_d_data_mes);
         $this->vc18_d_data_ano = ($this->vc18_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["vc18_d_data_ano"]:$this->vc18_d_data_ano);
         if($this->vc18_d_data_dia != ""){
            $this->vc18_d_data = $this->vc18_d_data_ano."-".$this->vc18_d_data_mes."-".$this->vc18_d_data_dia;
         }
       }
       $this->vc18_t_obs = ($this->vc18_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["vc18_t_obs"]:$this->vc18_t_obs);
     }else{
       $this->vc18_i_codigo = ($this->vc18_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc18_i_codigo"]:$this->vc18_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($vc18_i_codigo){ 
      $this->atualizacampos();
     if($this->vc18_i_aplica == null ){ 
       $this->erro_sql = " Campo Aplicação nao Informado.";
       $this->erro_campo = "vc18_i_aplica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc18_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "vc18_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc18_c_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "vc18_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc18_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "vc18_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc18_t_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "vc18_t_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($vc18_i_codigo == "" || $vc18_i_codigo == null ){
       $result = db_query("select nextval('vac_aplicaanula_vc18_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vac_aplicaanula_vc18_i_codigo_seq do campo: vc18_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->vc18_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vac_aplicaanula_vc18_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $vc18_i_codigo)){
         $this->erro_sql = " Campo vc18_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->vc18_i_codigo = $vc18_i_codigo; 
       }
     }
     if(($this->vc18_i_codigo == null) || ($this->vc18_i_codigo == "") ){ 
       $this->erro_sql = " Campo vc18_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vac_aplicaanula(
                                       vc18_i_codigo 
                                      ,vc18_i_aplica 
                                      ,vc18_i_usuario 
                                      ,vc18_c_hora 
                                      ,vc18_d_data 
                                      ,vc18_t_obs 
                       )
                values (
                                $this->vc18_i_codigo 
                               ,$this->vc18_i_aplica 
                               ,$this->vc18_i_usuario 
                               ,'$this->vc18_c_hora' 
                               ,".($this->vc18_d_data == "null" || $this->vc18_d_data == ""?"null":"'".$this->vc18_d_data."'")." 
                               ,'$this->vc18_t_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Anular aplicação ($this->vc18_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Anular aplicação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Anular aplicação ($this->vc18_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc18_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->vc18_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16891,'$this->vc18_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2972,16891,'','".AddSlashes(pg_result($resaco,0,'vc18_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2972,16887,'','".AddSlashes(pg_result($resaco,0,'vc18_i_aplica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2972,16888,'','".AddSlashes(pg_result($resaco,0,'vc18_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2972,16890,'','".AddSlashes(pg_result($resaco,0,'vc18_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2972,16889,'','".AddSlashes(pg_result($resaco,0,'vc18_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2972,17137,'','".AddSlashes(pg_result($resaco,0,'vc18_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($vc18_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update vac_aplicaanula set ";
     $virgula = "";
     if(trim($this->vc18_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc18_i_codigo"])){ 
       $sql  .= $virgula." vc18_i_codigo = $this->vc18_i_codigo ";
       $virgula = ",";
       if(trim($this->vc18_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "vc18_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc18_i_aplica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc18_i_aplica"])){ 
       $sql  .= $virgula." vc18_i_aplica = $this->vc18_i_aplica ";
       $virgula = ",";
       if(trim($this->vc18_i_aplica) == null ){ 
         $this->erro_sql = " Campo Aplicação nao Informado.";
         $this->erro_campo = "vc18_i_aplica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc18_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc18_i_usuario"])){ 
       $sql  .= $virgula." vc18_i_usuario = $this->vc18_i_usuario ";
       $virgula = ",";
       if(trim($this->vc18_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "vc18_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc18_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc18_c_hora"])){ 
       $sql  .= $virgula." vc18_c_hora = '$this->vc18_c_hora' ";
       $virgula = ",";
       if(trim($this->vc18_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "vc18_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc18_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc18_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["vc18_d_data_dia"] !="") ){ 
       $sql  .= $virgula." vc18_d_data = '$this->vc18_d_data' ";
       $virgula = ",";
       if(trim($this->vc18_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "vc18_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["vc18_d_data_dia"])){ 
         $sql  .= $virgula." vc18_d_data = null ";
         $virgula = ",";
         if(trim($this->vc18_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "vc18_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->vc18_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc18_t_obs"])){ 
       $sql  .= $virgula." vc18_t_obs = '$this->vc18_t_obs' ";
       $virgula = ",";
       if(trim($this->vc18_t_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "vc18_t_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($vc18_i_codigo!=null){
       $sql .= " vc18_i_codigo = $this->vc18_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->vc18_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16891,'$this->vc18_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc18_i_codigo"]) || $this->vc18_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2972,16891,'".AddSlashes(pg_result($resaco,$conresaco,'vc18_i_codigo'))."','$this->vc18_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc18_i_aplica"]) || $this->vc18_i_aplica != "")
           $resac = db_query("insert into db_acount values($acount,2972,16887,'".AddSlashes(pg_result($resaco,$conresaco,'vc18_i_aplica'))."','$this->vc18_i_aplica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc18_i_usuario"]) || $this->vc18_i_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2972,16888,'".AddSlashes(pg_result($resaco,$conresaco,'vc18_i_usuario'))."','$this->vc18_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc18_c_hora"]) || $this->vc18_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2972,16890,'".AddSlashes(pg_result($resaco,$conresaco,'vc18_c_hora'))."','$this->vc18_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc18_d_data"]) || $this->vc18_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2972,16889,'".AddSlashes(pg_result($resaco,$conresaco,'vc18_d_data'))."','$this->vc18_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc18_t_obs"]) || $this->vc18_t_obs != "")
           $resac = db_query("insert into db_acount values($acount,2972,17137,'".AddSlashes(pg_result($resaco,$conresaco,'vc18_t_obs'))."','$this->vc18_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Anular aplicação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc18_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Anular aplicação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc18_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc18_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($vc18_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($vc18_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16891,'$vc18_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2972,16891,'','".AddSlashes(pg_result($resaco,$iresaco,'vc18_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2972,16887,'','".AddSlashes(pg_result($resaco,$iresaco,'vc18_i_aplica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2972,16888,'','".AddSlashes(pg_result($resaco,$iresaco,'vc18_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2972,16890,'','".AddSlashes(pg_result($resaco,$iresaco,'vc18_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2972,16889,'','".AddSlashes(pg_result($resaco,$iresaco,'vc18_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2972,17137,'','".AddSlashes(pg_result($resaco,$iresaco,'vc18_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vac_aplicaanula
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($vc18_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " vc18_i_codigo = $vc18_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Anular aplicação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$vc18_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Anular aplicação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$vc18_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$vc18_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:vac_aplicaanula";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $vc18_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_aplicaanula ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = vac_aplicaanula.vc18_i_usuario";
     $sql .= "      inner join vac_aplica  on  vac_aplica.vc16_i_codigo = vac_aplicaanula.vc18_i_aplica";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = vac_aplica.vc16_i_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = vac_aplica.vc16_i_departamento";
     $sql .= "      inner join vac_vacinadose  on  vac_vacinadose.vc07_i_codigo = vac_aplica.vc16_i_dosevacina";
     $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = vac_aplica.vc16_i_cgs";
     $sql2 = "";
     if($dbwhere==""){
       if($vc18_i_codigo!=null ){
         $sql2 .= " where vac_aplicaanula.vc18_i_codigo = $vc18_i_codigo "; 
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
   function sql_query_file ( $vc18_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_aplicaanula ";
     $sql2 = "";
     if($dbwhere==""){
       if($vc18_i_codigo!=null ){
         $sql2 .= " where vac_aplicaanula.vc18_i_codigo = $vc18_i_codigo "; 
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