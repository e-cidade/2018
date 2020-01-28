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

//MODULO: Merenda
//CLASSE DA ENTIDADE mer_cardapioaluno
class cl_mer_cardapioaluno { 
   // cria variaveis de erro 
   var $rotulo          = null; 
   var $query_sql       = null; 
   var $numrows         = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status     = null; 
   var $erro_sql        = null; 
   var $erro_banco      = null;  
   var $erro_msg        = null;  
   var $erro_campo      = null;  
   var $pagina_retorno  = null; 
   // cria variaveis do arquivo 
   var $me11_i_codigo        = 0; 
   var $me11_i_cardapiodia        = 0; 
   var $me11_i_matricula        = 0; 
   var $me11_d_data_dia    = null; 
   var $me11_d_data_mes    = null; 
   var $me11_d_data_ano    = null; 
   var $me11_d_data        = null; 
   var $me11_i_usuario        = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me11_i_codigo = int4 = Código 
                 me11_i_cardapiodia = int4 = Cardápio 
                 me11_i_matricula = int4 = Matricula 
                 me11_d_data = date = Data 
                 me11_i_usuario = int4 = Usuário 
                 ";
   //funcao construtor da classe 
   function cl_mer_cardapioaluno() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_cardapioaluno"); 
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
       $this->me11_i_codigo = ($this->me11_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me11_i_codigo"]:$this->me11_i_codigo);
       $this->me11_i_cardapiodia = ($this->me11_i_cardapiodia == ""?@$GLOBALS["HTTP_POST_VARS"]["me11_i_cardapiodia"]:$this->me11_i_cardapiodia);
       $this->me11_i_matricula = ($this->me11_i_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["me11_i_matricula"]:$this->me11_i_matricula);
       if($this->me11_d_data == ""){
         $this->me11_d_data_dia = ($this->me11_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["me11_d_data_dia"]:$this->me11_d_data_dia);
         $this->me11_d_data_mes = ($this->me11_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["me11_d_data_mes"]:$this->me11_d_data_mes);
         $this->me11_d_data_ano = ($this->me11_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["me11_d_data_ano"]:$this->me11_d_data_ano);
         if($this->me11_d_data_dia != ""){
            $this->me11_d_data = $this->me11_d_data_ano."-".$this->me11_d_data_mes."-".$this->me11_d_data_dia;
         }
       }
       $this->me11_i_usuario = ($this->me11_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["me11_i_usuario"]:$this->me11_i_usuario);
     }else{
       $this->me11_i_codigo = ($this->me11_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me11_i_codigo"]:$this->me11_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me11_i_codigo){ 
      $this->atualizacampos();
     if($this->me11_i_cardapiodia == null ){ 
       $this->erro_sql = " Campo Cardápio nao Informado.";
       $this->erro_campo = "me11_i_cardapiodia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me11_i_matricula == null ){ 
       $this->erro_sql = " Campo Matricula nao Informado.";
       $this->erro_campo = "me11_i_matricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me11_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "me11_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me11_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "me11_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me11_i_codigo == "" || $me11_i_codigo == null ){
       $result = db_query("select nextval('mercardapioaluno_me11_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mercardapioaluno_me11_codigo_seq do campo: me11_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me11_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mercardapioaluno_me11_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me11_i_codigo)){
         $this->erro_sql = " Campo me11_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me11_i_codigo = $me11_i_codigo; 
       }
     }
     if(($this->me11_i_codigo == null) || ($this->me11_i_codigo == "") ){ 
       $this->erro_sql = " Campo me11_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_cardapioaluno(
                                       me11_i_codigo 
                                      ,me11_i_cardapiodia 
                                      ,me11_i_matricula 
                                      ,me11_d_data 
                                      ,me11_i_usuario 
                       )
                values (
                                $this->me11_i_codigo 
                               ,$this->me11_i_cardapiodia 
                               ,$this->me11_i_matricula 
                               ,".($this->me11_d_data == "null" || $this->me11_d_data == ""?"null":"'".$this->me11_d_data."'")." 
                               ,$this->me11_i_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mer_cardapioaluno ($this->me11_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mer_cardapioaluno já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mer_cardapioaluno ($this->me11_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me11_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me11_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12747,'$this->me11_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2230,12747,'','".AddSlashes(pg_result($resaco,0,'me11_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2230,12748,'','".AddSlashes(pg_result($resaco,0,'me11_i_cardapiodia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2230,12749,'','".AddSlashes(pg_result($resaco,0,'me11_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2230,13777,'','".AddSlashes(pg_result($resaco,0,'me11_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2230,14483,'','".AddSlashes(pg_result($resaco,0,'me11_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me11_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_cardapioaluno set ";
     $virgula = "";
     if(trim($this->me11_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me11_i_codigo"])){ 
       $sql  .= $virgula." me11_i_codigo = $this->me11_i_codigo ";
       $virgula = ",";
       if(trim($this->me11_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "me11_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me11_i_cardapiodia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me11_i_cardapiodia"])){ 
       $sql  .= $virgula." me11_i_cardapiodia = $this->me11_i_cardapiodia ";
       $virgula = ",";
       if(trim($this->me11_i_cardapiodia) == null ){ 
         $this->erro_sql = " Campo Cardápio nao Informado.";
         $this->erro_campo = "me11_i_cardapiodia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me11_i_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me11_i_matricula"])){ 
       $sql  .= $virgula." me11_i_matricula = $this->me11_i_matricula ";
       $virgula = ",";
       if(trim($this->me11_i_matricula) == null ){ 
         $this->erro_sql = " Campo Matricula nao Informado.";
         $this->erro_campo = "me11_i_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me11_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me11_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["me11_d_data_dia"] !="") ){ 
       $sql  .= $virgula." me11_d_data = '$this->me11_d_data' ";
       $virgula = ",";
       if(trim($this->me11_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "me11_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["me11_d_data_dia"])){ 
         $sql  .= $virgula." me11_d_data = null ";
         $virgula = ",";
         if(trim($this->me11_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "me11_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->me11_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me11_i_usuario"])){ 
       $sql  .= $virgula." me11_i_usuario = $this->me11_i_usuario ";
       $virgula = ",";
       if(trim($this->me11_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "me11_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me11_i_codigo!=null){
       $sql .= " me11_i_codigo = $this->me11_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me11_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12747,'$this->me11_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me11_i_codigo"]) || $this->me11_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2230,12747,'".AddSlashes(pg_result($resaco,$conresaco,'me11_i_codigo'))."','$this->me11_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me11_i_cardapiodia"]) || $this->me11_i_cardapiodia != "")
           $resac = db_query("insert into db_acount values($acount,2230,12748,'".AddSlashes(pg_result($resaco,$conresaco,'me11_i_cardapiodia'))."','$this->me11_i_cardapiodia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me11_i_matricula"]) || $this->me11_i_matricula != "")
           $resac = db_query("insert into db_acount values($acount,2230,12749,'".AddSlashes(pg_result($resaco,$conresaco,'me11_i_matricula'))."','$this->me11_i_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me11_d_data"]) || $this->me11_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2230,13777,'".AddSlashes(pg_result($resaco,$conresaco,'me11_d_data'))."','$this->me11_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me11_i_usuario"]) || $this->me11_i_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2230,14483,'".AddSlashes(pg_result($resaco,$conresaco,'me11_i_usuario'))."','$this->me11_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_cardapioaluno nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me11_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_cardapioaluno nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me11_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me11_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me11_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me11_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12747,'$me11_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2230,12747,'','".AddSlashes(pg_result($resaco,$iresaco,'me11_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2230,12748,'','".AddSlashes(pg_result($resaco,$iresaco,'me11_i_cardapiodia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2230,12749,'','".AddSlashes(pg_result($resaco,$iresaco,'me11_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2230,13777,'','".AddSlashes(pg_result($resaco,$iresaco,'me11_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2230,14483,'','".AddSlashes(pg_result($resaco,$iresaco,'me11_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_cardapioaluno
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me11_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me11_i_codigo = $me11_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_cardapioaluno nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me11_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_cardapioaluno nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me11_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me11_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_cardapioaluno";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me11_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_cardapioaluno ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = mer_cardapioaluno.me11_i_usuario";
     $sql .= "      inner join mer_cardapiodia  on  mer_cardapiodia.me12_i_codigo = mer_cardapioaluno.me11_i_cardapiodia";
     $sql .= "      inner join matricula  on  matricula.ed60_i_codigo = mer_cardapioaluno.me11_i_matricula";
     $sql .= "      inner join matriculaserie  on  matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo";
     $sql .= "                                and  matriculaserie.ed221_c_origem = 'S'";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = matriculaserie.ed221_i_serie";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql .= "      inner join mer_tprefeicao  on  mer_tprefeicao.me03_i_codigo = mer_cardapiodia.me12_i_tprefeicao";
     $sql .= "      inner join mer_cardapio  on   mer_cardapio.me01_i_codigo = mer_cardapiodia.me12_i_cardapio";
     $sql .= "      inner join mer_tipocardapio  on   mer_tipocardapio.me27_i_codigo = mer_cardapio.me01_i_tipocardapio";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = matricula.ed60_i_aluno";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = matricula.ed60_i_turma";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
     $sql .= "      left join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
     $sql2 = "";
     if($dbwhere==""){
       if($me11_i_codigo!=null ){
         $sql2 .= " where mer_cardapioaluno.me11_i_codigo = $me11_i_codigo "; 
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
   function sql_query_file ( $me11_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_cardapioaluno ";
     $sql2 = "";
     if($dbwhere==""){
       if($me11_i_codigo!=null ){
         $sql2 .= " where mer_cardapioaluno.me11_i_codigo = $me11_i_codigo "; 
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