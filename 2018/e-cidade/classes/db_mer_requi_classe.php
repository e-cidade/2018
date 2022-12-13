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

//MODULO: merenda
//CLASSE DA ENTIDADE mer_requi
class cl_mer_requi { 
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
   var $me16_i_codigo = 0; 
   var $me16_t_obs = null; 
   var $me16_d_data_dia = null; 
   var $me16_d_data_mes = null; 
   var $me16_d_data_ano = null; 
   var $me16_d_data = null; 
   var $me16_c_hora = null; 
   var $me16_c_tiposaida = null; 
   var $me16_i_escola = 0; 
   var $me16_i_dbusuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me16_i_codigo = int4 = Código 
                 me16_t_obs = text = Obsevação 
                 me16_d_data = date = Data 
                 me16_c_hora = char(5) = Hora 
                 me16_c_tiposaida = char(2) = Tipo de saída 
                 me16_i_escola = int4 = Escola 
                 me16_i_dbusuario = int4 = Usuário 
                 ";
   //funcao construtor da classe 
   function cl_mer_requi() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_requi"); 
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
       $this->me16_i_codigo = ($this->me16_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me16_i_codigo"]:$this->me16_i_codigo);
       $this->me16_t_obs = ($this->me16_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["me16_t_obs"]:$this->me16_t_obs);
       if($this->me16_d_data == ""){
         $this->me16_d_data_dia = ($this->me16_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["me16_d_data_dia"]:$this->me16_d_data_dia);
         $this->me16_d_data_mes = ($this->me16_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["me16_d_data_mes"]:$this->me16_d_data_mes);
         $this->me16_d_data_ano = ($this->me16_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["me16_d_data_ano"]:$this->me16_d_data_ano);
         if($this->me16_d_data_dia != ""){
            $this->me16_d_data = $this->me16_d_data_ano."-".$this->me16_d_data_mes."-".$this->me16_d_data_dia;
         }
       }
       $this->me16_c_hora = ($this->me16_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["me16_c_hora"]:$this->me16_c_hora);
       $this->me16_c_tiposaida = ($this->me16_c_tiposaida == ""?@$GLOBALS["HTTP_POST_VARS"]["me16_c_tiposaida"]:$this->me16_c_tiposaida);
       $this->me16_i_escola = ($this->me16_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["me16_i_escola"]:$this->me16_i_escola);
       $this->me16_i_dbusuario = ($this->me16_i_dbusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["me16_i_dbusuario"]:$this->me16_i_dbusuario);
     }else{
       $this->me16_i_codigo = ($this->me16_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me16_i_codigo"]:$this->me16_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me16_i_codigo){ 
      $this->atualizacampos();
     if($this->me16_t_obs == null ){ 
       $this->erro_sql = " Campo Obsevação nao Informado.";
       $this->erro_campo = "me16_t_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me16_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "me16_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me16_c_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "me16_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me16_c_tiposaida == null ){ 
       $this->erro_sql = " Campo Tipo de saída nao Informado.";
       $this->erro_campo = "me16_c_tiposaida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me16_i_escola == null ){ 
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "me16_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me16_i_dbusuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "me16_i_dbusuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me16_i_codigo == "" || $me16_i_codigo == null ){
       $result = db_query("select nextval('mer_requi_me16_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mer_requi_me16_codigo_seq do campo: me16_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me16_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mer_requi_me16_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me16_i_codigo)){
         $this->erro_sql = " Campo me16_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me16_i_codigo = $me16_i_codigo; 
       }
     }
     if(($this->me16_i_codigo == null) || ($this->me16_i_codigo == "") ){ 
       $this->erro_sql = " Campo me16_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_requi(
                                       me16_i_codigo 
                                      ,me16_t_obs 
                                      ,me16_d_data 
                                      ,me16_c_hora 
                                      ,me16_c_tiposaida 
                                      ,me16_i_escola 
                                      ,me16_i_dbusuario 
                       )
                values (
                                $this->me16_i_codigo 
                               ,'$this->me16_t_obs' 
                               ,".($this->me16_d_data == "null" || $this->me16_d_data == ""?"null":"'".$this->me16_d_data."'")." 
                               ,'$this->me16_c_hora' 
                               ,'$this->me16_c_tiposaida' 
                               ,$this->me16_i_escola 
                               ,$this->me16_i_dbusuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mer requi ($this->me16_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mer requi já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mer requi ($this->me16_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me16_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me16_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12718,'$this->me16_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2225,12718,'','".AddSlashes(pg_result($resaco,0,'me16_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2225,12719,'','".AddSlashes(pg_result($resaco,0,'me16_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2225,12721,'','".AddSlashes(pg_result($resaco,0,'me16_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2225,12720,'','".AddSlashes(pg_result($resaco,0,'me16_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2225,12722,'','".AddSlashes(pg_result($resaco,0,'me16_c_tiposaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2225,12723,'','".AddSlashes(pg_result($resaco,0,'me16_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2225,12724,'','".AddSlashes(pg_result($resaco,0,'me16_i_dbusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me16_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_requi set ";
     $virgula = "";
     if(trim($this->me16_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me16_i_codigo"])){ 
       $sql  .= $virgula." me16_i_codigo = $this->me16_i_codigo ";
       $virgula = ",";
       if(trim($this->me16_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "me16_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me16_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me16_t_obs"])){ 
       $sql  .= $virgula." me16_t_obs = '$this->me16_t_obs' ";
       $virgula = ",";
       if(trim($this->me16_t_obs) == null ){ 
         $this->erro_sql = " Campo Obsevação nao Informado.";
         $this->erro_campo = "me16_t_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me16_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me16_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["me16_d_data_dia"] !="") ){ 
       $sql  .= $virgula." me16_d_data = '$this->me16_d_data' ";
       $virgula = ",";
       if(trim($this->me16_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "me16_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["me16_d_data_dia"])){ 
         $sql  .= $virgula." me16_d_data = null ";
         $virgula = ",";
         if(trim($this->me16_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "me16_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->me16_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me16_c_hora"])){ 
       $sql  .= $virgula." me16_c_hora = '$this->me16_c_hora' ";
       $virgula = ",";
       if(trim($this->me16_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "me16_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me16_c_tiposaida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me16_c_tiposaida"])){ 
       $sql  .= $virgula." me16_c_tiposaida = '$this->me16_c_tiposaida' ";
       $virgula = ",";
       if(trim($this->me16_c_tiposaida) == null ){ 
         $this->erro_sql = " Campo Tipo de saída nao Informado.";
         $this->erro_campo = "me16_c_tiposaida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me16_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me16_i_escola"])){ 
       $sql  .= $virgula." me16_i_escola = $this->me16_i_escola ";
       $virgula = ",";
       if(trim($this->me16_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "me16_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me16_i_dbusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me16_i_dbusuario"])){ 
       $sql  .= $virgula." me16_i_dbusuario = $this->me16_i_dbusuario ";
       $virgula = ",";
       if(trim($this->me16_i_dbusuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "me16_i_dbusuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me16_i_codigo!=null){
       $sql .= " me16_i_codigo = $this->me16_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me16_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12718,'$this->me16_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me16_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2225,12718,'".AddSlashes(pg_result($resaco,$conresaco,'me16_i_codigo'))."','$this->me16_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me16_t_obs"]))
           $resac = db_query("insert into db_acount values($acount,2225,12719,'".AddSlashes(pg_result($resaco,$conresaco,'me16_t_obs'))."','$this->me16_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me16_d_data"]))
           $resac = db_query("insert into db_acount values($acount,2225,12721,'".AddSlashes(pg_result($resaco,$conresaco,'me16_d_data'))."','$this->me16_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me16_c_hora"]))
           $resac = db_query("insert into db_acount values($acount,2225,12720,'".AddSlashes(pg_result($resaco,$conresaco,'me16_c_hora'))."','$this->me16_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me16_c_tiposaida"]))
           $resac = db_query("insert into db_acount values($acount,2225,12722,'".AddSlashes(pg_result($resaco,$conresaco,'me16_c_tiposaida'))."','$this->me16_c_tiposaida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me16_i_escola"]))
           $resac = db_query("insert into db_acount values($acount,2225,12723,'".AddSlashes(pg_result($resaco,$conresaco,'me16_i_escola'))."','$this->me16_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me16_i_dbusuario"]))
           $resac = db_query("insert into db_acount values($acount,2225,12724,'".AddSlashes(pg_result($resaco,$conresaco,'me16_i_dbusuario'))."','$this->me16_i_dbusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer requi nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me16_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer requi nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me16_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me16_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me16_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me16_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12718,'$me16_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2225,12718,'','".AddSlashes(pg_result($resaco,$iresaco,'me16_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2225,12719,'','".AddSlashes(pg_result($resaco,$iresaco,'me16_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2225,12721,'','".AddSlashes(pg_result($resaco,$iresaco,'me16_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2225,12720,'','".AddSlashes(pg_result($resaco,$iresaco,'me16_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2225,12722,'','".AddSlashes(pg_result($resaco,$iresaco,'me16_c_tiposaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2225,12723,'','".AddSlashes(pg_result($resaco,$iresaco,'me16_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2225,12724,'','".AddSlashes(pg_result($resaco,$iresaco,'me16_i_dbusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_requi
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me16_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me16_i_codigo = $me16_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer requi nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me16_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer requi nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me16_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me16_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_requi";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me16_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_requi ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = mer_requi.me16_i_dbusuario";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = mer_requi.me16_i_escola";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($me16_i_codigo!=null ){
         $sql2 .= " where mer_requi.me16_i_codigo = $me16_i_codigo "; 
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
   function sql_query_file ( $me16_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_requi ";
     $sql2 = "";
     if($dbwhere==""){
       if($me16_i_codigo!=null ){
         $sql2 .= " where mer_requi.me16_i_codigo = $me16_i_codigo "; 
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