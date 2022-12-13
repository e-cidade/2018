<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: laboratorio
//CLASSE DA ENTIDADE lab_conferencia
class cl_lab_conferencia { 
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
   var $la47_i_codigo = 0; 
   var $la47_i_requiitem = 0; 
   var $la47_i_login = 0; 
   var $la47_d_data_dia = null; 
   var $la47_d_data_mes = null; 
   var $la47_d_data_ano = null; 
   var $la47_d_data = null; 
   var $la47_c_hora = null; 
   var $la47_i_resultado = 0; 
   var $la47_t_observacao = null; 
   var $la47_i_procedimento = 0; 
   var $la47_i_cid = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la47_i_codigo = int4 = Código 
                 la47_i_requiitem = int4 = Exame 
                 la47_i_login = int4 = Usuário 
                 la47_d_data = date = Data 
                 la47_c_hora = char(5) = Hora 
                 la47_i_resultado = int4 = Resultado 
                 la47_t_observacao = text = Observação 
                 la47_i_procedimento = int8 = Procedimento 
                 la47_i_cid = int8 = CID 
                 ";
   //funcao construtor da classe 
   function cl_lab_conferencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_conferencia"); 
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
       $this->la47_i_codigo = ($this->la47_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la47_i_codigo"]:$this->la47_i_codigo);
       $this->la47_i_requiitem = ($this->la47_i_requiitem == ""?@$GLOBALS["HTTP_POST_VARS"]["la47_i_requiitem"]:$this->la47_i_requiitem);
       $this->la47_i_login = ($this->la47_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["la47_i_login"]:$this->la47_i_login);
       if($this->la47_d_data == ""){
         $this->la47_d_data_dia = ($this->la47_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la47_d_data_dia"]:$this->la47_d_data_dia);
         $this->la47_d_data_mes = ($this->la47_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la47_d_data_mes"]:$this->la47_d_data_mes);
         $this->la47_d_data_ano = ($this->la47_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la47_d_data_ano"]:$this->la47_d_data_ano);
         if($this->la47_d_data_dia != ""){
            $this->la47_d_data = $this->la47_d_data_ano."-".$this->la47_d_data_mes."-".$this->la47_d_data_dia;
         }
       }
       $this->la47_c_hora = ($this->la47_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["la47_c_hora"]:$this->la47_c_hora);
       $this->la47_i_resultado = ($this->la47_i_resultado == ""?@$GLOBALS["HTTP_POST_VARS"]["la47_i_resultado"]:$this->la47_i_resultado);
       $this->la47_t_observacao = ($this->la47_t_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["la47_t_observacao"]:$this->la47_t_observacao);
       $this->la47_i_procedimento = ($this->la47_i_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["la47_i_procedimento"]:$this->la47_i_procedimento);
       $this->la47_i_cid = ($this->la47_i_cid == ""?@$GLOBALS["HTTP_POST_VARS"]["la47_i_cid"]:$this->la47_i_cid);
     }else{
       $this->la47_i_codigo = ($this->la47_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la47_i_codigo"]:$this->la47_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la47_i_codigo){ 
      $this->atualizacampos();
     if($this->la47_i_requiitem == null ){ 
       $this->erro_sql = " Campo Exame nao Informado.";
       $this->erro_campo = "la47_i_requiitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la47_i_login == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "la47_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la47_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "la47_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la47_c_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "la47_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la47_i_resultado == null ){ 
       $this->erro_sql = " Campo Resultado nao Informado.";
       $this->erro_campo = "la47_i_resultado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la47_i_procedimento == null ){ 
       $this->erro_sql = " Campo Procedimento nao Informado.";
       $this->erro_campo = "la47_i_procedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la47_i_cid == null ){ 
       $this->la47_i_cid = "null";
     }
     if($la47_i_codigo == "" || $la47_i_codigo == null ){
       $result = db_query("select nextval('lab_conferencia_la47_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_conferencia_la47_i_codigo_seq do campo: la47_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la47_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_conferencia_la47_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la47_i_codigo)){
         $this->erro_sql = " Campo la47_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la47_i_codigo = $la47_i_codigo; 
       }
     }
     if(($this->la47_i_codigo == null) || ($this->la47_i_codigo == "") ){ 
       $this->erro_sql = " Campo la47_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_conferencia(
                                       la47_i_codigo 
                                      ,la47_i_requiitem 
                                      ,la47_i_login 
                                      ,la47_d_data 
                                      ,la47_c_hora 
                                      ,la47_i_resultado 
                                      ,la47_t_observacao 
                                      ,la47_i_procedimento 
                                      ,la47_i_cid 
                       )
                values (
                                $this->la47_i_codigo 
                               ,$this->la47_i_requiitem 
                               ,$this->la47_i_login 
                               ,".($this->la47_d_data == "null" || $this->la47_d_data == ""?"null":"'".$this->la47_d_data."'")." 
                               ,'$this->la47_c_hora' 
                               ,$this->la47_i_resultado 
                               ,'$this->la47_t_observacao' 
                               ,$this->la47_i_procedimento 
                               ,$this->la47_i_cid 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Conferência ($this->la47_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Conferência já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Conferência ($this->la47_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la47_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->la47_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16474,'$this->la47_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2890,16474,'','".AddSlashes(pg_result($resaco,0,'la47_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2890,16475,'','".AddSlashes(pg_result($resaco,0,'la47_i_requiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2890,16476,'','".AddSlashes(pg_result($resaco,0,'la47_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2890,16477,'','".AddSlashes(pg_result($resaco,0,'la47_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2890,16478,'','".AddSlashes(pg_result($resaco,0,'la47_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2890,16479,'','".AddSlashes(pg_result($resaco,0,'la47_i_resultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2890,16480,'','".AddSlashes(pg_result($resaco,0,'la47_t_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2890,16764,'','".AddSlashes(pg_result($resaco,0,'la47_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2890,16765,'','".AddSlashes(pg_result($resaco,0,'la47_i_cid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la47_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_conferencia set ";
     $virgula = "";
     if(trim($this->la47_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la47_i_codigo"])){ 
       $sql  .= $virgula." la47_i_codigo = $this->la47_i_codigo ";
       $virgula = ",";
       if(trim($this->la47_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "la47_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la47_i_requiitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la47_i_requiitem"])){ 
       $sql  .= $virgula." la47_i_requiitem = $this->la47_i_requiitem ";
       $virgula = ",";
       if(trim($this->la47_i_requiitem) == null ){ 
         $this->erro_sql = " Campo Exame nao Informado.";
         $this->erro_campo = "la47_i_requiitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la47_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la47_i_login"])){ 
       $sql  .= $virgula." la47_i_login = $this->la47_i_login ";
       $virgula = ",";
       if(trim($this->la47_i_login) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "la47_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la47_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la47_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la47_d_data_dia"] !="") ){ 
       $sql  .= $virgula." la47_d_data = '$this->la47_d_data' ";
       $virgula = ",";
       if(trim($this->la47_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "la47_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la47_d_data_dia"])){ 
         $sql  .= $virgula." la47_d_data = null ";
         $virgula = ",";
         if(trim($this->la47_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "la47_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->la47_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la47_c_hora"])){ 
       $sql  .= $virgula." la47_c_hora = '$this->la47_c_hora' ";
       $virgula = ",";
       if(trim($this->la47_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "la47_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la47_i_resultado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la47_i_resultado"])){ 
       $sql  .= $virgula." la47_i_resultado = $this->la47_i_resultado ";
       $virgula = ",";
       if(trim($this->la47_i_resultado) == null ){ 
         $this->erro_sql = " Campo Resultado nao Informado.";
         $this->erro_campo = "la47_i_resultado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la47_t_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la47_t_observacao"])){ 
       $sql  .= $virgula." la47_t_observacao = '$this->la47_t_observacao' ";
       $virgula = ",";
     }
     if(trim($this->la47_i_procedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la47_i_procedimento"])){ 
       $sql  .= $virgula." la47_i_procedimento = $this->la47_i_procedimento ";
       $virgula = ",";
       if(trim($this->la47_i_procedimento) == null ){ 
         $this->erro_sql = " Campo Procedimento nao Informado.";
         $this->erro_campo = "la47_i_procedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la47_i_cid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la47_i_cid"])){ 
        if(trim($this->la47_i_cid)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la47_i_cid"])){ 
           $this->la47_i_cid = "null" ; 
        } 
       $sql  .= $virgula." la47_i_cid = $this->la47_i_cid ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($la47_i_codigo!=null){
       $sql .= " la47_i_codigo = $this->la47_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->la47_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16474,'$this->la47_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la47_i_codigo"]) || $this->la47_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2890,16474,'".AddSlashes(pg_result($resaco,$conresaco,'la47_i_codigo'))."','$this->la47_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la47_i_requiitem"]) || $this->la47_i_requiitem != "")
           $resac = db_query("insert into db_acount values($acount,2890,16475,'".AddSlashes(pg_result($resaco,$conresaco,'la47_i_requiitem'))."','$this->la47_i_requiitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la47_i_login"]) || $this->la47_i_login != "")
           $resac = db_query("insert into db_acount values($acount,2890,16476,'".AddSlashes(pg_result($resaco,$conresaco,'la47_i_login'))."','$this->la47_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la47_d_data"]) || $this->la47_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2890,16477,'".AddSlashes(pg_result($resaco,$conresaco,'la47_d_data'))."','$this->la47_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la47_c_hora"]) || $this->la47_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2890,16478,'".AddSlashes(pg_result($resaco,$conresaco,'la47_c_hora'))."','$this->la47_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la47_i_resultado"]) || $this->la47_i_resultado != "")
           $resac = db_query("insert into db_acount values($acount,2890,16479,'".AddSlashes(pg_result($resaco,$conresaco,'la47_i_resultado'))."','$this->la47_i_resultado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la47_t_observacao"]) || $this->la47_t_observacao != "")
           $resac = db_query("insert into db_acount values($acount,2890,16480,'".AddSlashes(pg_result($resaco,$conresaco,'la47_t_observacao'))."','$this->la47_t_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la47_i_procedimento"]) || $this->la47_i_procedimento != "")
           $resac = db_query("insert into db_acount values($acount,2890,16764,'".AddSlashes(pg_result($resaco,$conresaco,'la47_i_procedimento'))."','$this->la47_i_procedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la47_i_cid"]) || $this->la47_i_cid != "")
           $resac = db_query("insert into db_acount values($acount,2890,16765,'".AddSlashes(pg_result($resaco,$conresaco,'la47_i_cid'))."','$this->la47_i_cid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Conferência nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la47_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Conferência nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la47_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la47_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la47_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($la47_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16474,'$la47_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2890,16474,'','".AddSlashes(pg_result($resaco,$iresaco,'la47_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2890,16475,'','".AddSlashes(pg_result($resaco,$iresaco,'la47_i_requiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2890,16476,'','".AddSlashes(pg_result($resaco,$iresaco,'la47_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2890,16477,'','".AddSlashes(pg_result($resaco,$iresaco,'la47_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2890,16478,'','".AddSlashes(pg_result($resaco,$iresaco,'la47_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2890,16479,'','".AddSlashes(pg_result($resaco,$iresaco,'la47_i_resultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2890,16480,'','".AddSlashes(pg_result($resaco,$iresaco,'la47_t_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2890,16764,'','".AddSlashes(pg_result($resaco,$iresaco,'la47_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2890,16765,'','".AddSlashes(pg_result($resaco,$iresaco,'la47_i_cid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lab_conferencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la47_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la47_i_codigo = $la47_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Conferência nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la47_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Conferência nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la47_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la47_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_conferencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la47_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_conferencia ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = lab_conferencia.la47_i_login";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = lab_conferencia.la47_i_procedimento";
     $sql .= "      left  join sau_cid  on  sau_cid.sd70_i_codigo = lab_conferencia.la47_i_cid";
     $sql .= "      inner join lab_requiitem  on  lab_requiitem.la21_i_codigo = lab_conferencia.la47_i_requiitem";
     $sql .= "      inner join sau_financiamento  on  sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento";
     $sql .= "      left  join sau_rubrica  on  sau_rubrica.sd64_i_codigo = sau_procedimento.sd63_i_rubrica";
     $sql .= "      inner join sau_complexidade  on  sau_complexidade.sd69_i_codigo = sau_procedimento.sd63_i_complexidade";
     $sql .= "      inner join lab_setorexame  on  lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame";
     $sql .= "      inner join lab_requisicao  on  lab_requisicao.la22_i_codigo = lab_requiitem.la21_i_requisicao";
     $sql2 = "";
     if($dbwhere==""){
       if($la47_i_codigo!=null ){
         $sql2 .= " where lab_conferencia.la47_i_codigo = $la47_i_codigo "; 
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
   function sql_query_file ( $la47_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_conferencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($la47_i_codigo!=null ){
         $sql2 .= " where lab_conferencia.la47_i_codigo = $la47_i_codigo "; 
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

  function sql_query_exames_conferidos ( $la47_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from lab_conferencia ";
    //    $sql .= " inner join lab_requiitem     on lab_requiitem.la21_i_codigo      = lab_conferencia.la47_i_requiitem ";
    //    $sql .= " inner join lab_setorexame    on lab_setorexame.la09_i_codigo     = lab_requiitem.la21_i_setorexame ";
    //    $sql .= " inner join lab_exame         on lab_exame.la08_i_codigo          = lab_setorexame.la09_i_exame ";
    //    $sql .= " inner join lab_exameproced   on lab_exameproced.la53_i_exame     = lab_exame.la08_i_codigo ";
    //    $sql .= " inner join sau_procedimento  on sau_procedimento.sd63_i_codigo   = lab_exameproced.la53_i_procedimento ";
    $sql .= " inner join sau_procedimento  on sau_procedimento.sd63_i_codigo   = lab_conferencia.la47_i_procedimento ";
    $sql .= " inner join sau_financiamento on sau_financiamento.sd65_i_codigo  = sau_procedimento.sd63_i_financiamento ";

    $sql2 = "";
    if($dbwhere==""){
      if($la47_i_codigo!=null ){
        $sql2 .= " where lab_conferencia.la47_i_codigo = $la47_i_codigo ";
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