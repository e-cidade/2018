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
//CLASSE DA ENTIDADE mer_modpreparo
class cl_mer_modpreparo { 
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
   var $me05_i_codigo        = 0; 
   var $me05_f_porcao        = 0; 
   var $me05_c_natureza        = null; 
   var $me05_t_obs        = null; 
   var $me05_i_cardapio        = 0; 
   var $me05_i_alimento        = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me05_i_codigo = int4 = Código 
                 me05_f_porcao = float4 = Porção 
                 me05_c_natureza = char(10) = Natureza 
                 me05_t_obs = text = Descrição 
                 me05_i_cardapio = int4 = Refeição 
                 me05_i_alimento = int4 = Alimento 
                 ";
   //funcao construtor da classe 
   function cl_mer_modpreparo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_modpreparo"); 
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
       $this->me05_i_codigo = ($this->me05_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me05_i_codigo"]:$this->me05_i_codigo);
       $this->me05_f_porcao = ($this->me05_f_porcao == ""?@$GLOBALS["HTTP_POST_VARS"]["me05_f_porcao"]:$this->me05_f_porcao);
       $this->me05_c_natureza = ($this->me05_c_natureza == ""?@$GLOBALS["HTTP_POST_VARS"]["me05_c_natureza"]:$this->me05_c_natureza);
       $this->me05_t_obs = ($this->me05_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["me05_t_obs"]:$this->me05_t_obs);
       $this->me05_i_cardapio = ($this->me05_i_cardapio == ""?@$GLOBALS["HTTP_POST_VARS"]["me05_i_cardapio"]:$this->me05_i_cardapio);
       $this->me05_i_alimento = ($this->me05_i_alimento == ""?@$GLOBALS["HTTP_POST_VARS"]["me05_i_alimento"]:$this->me05_i_alimento);
     }else{
       $this->me05_i_codigo = ($this->me05_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me05_i_codigo"]:$this->me05_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me05_i_codigo){ 
      $this->atualizacampos();
     if($this->me05_f_porcao == null ){ 
       $this->erro_sql = " Campo Porção nao Informado.";
       $this->erro_campo = "me05_f_porcao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me05_c_natureza == null ){ 
       $this->erro_sql = " Campo Natureza nao Informado.";
       $this->erro_campo = "me05_c_natureza";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me05_t_obs == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "me05_t_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me05_i_cardapio == null ){ 
       $this->erro_sql = " Campo Refeição nao Informado.";
       $this->erro_campo = "me05_i_cardapio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me05_i_alimento == null ){ 
       $this->erro_sql = " Campo Alimento nao Informado.";
       $this->erro_campo = "me05_i_alimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me05_i_codigo == "" || $me05_i_codigo == null ){
       $result = db_query("select nextval('mermodpreparo_me05_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mermodpreparo_me05_codigo_seq do campo: me05_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me05_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mermodpreparo_me05_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me05_i_codigo)){
         $this->erro_sql = " Campo me05_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me05_i_codigo = $me05_i_codigo; 
       }
     }
     if(($this->me05_i_codigo == null) || ($this->me05_i_codigo == "") ){ 
       $this->erro_sql = " Campo me05_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_modpreparo(
                                       me05_i_codigo 
                                      ,me05_f_porcao 
                                      ,me05_c_natureza 
                                      ,me05_t_obs 
                                      ,me05_i_cardapio 
                                      ,me05_i_alimento 
                       )
                values (
                                $this->me05_i_codigo 
                               ,$this->me05_f_porcao 
                               ,'$this->me05_c_natureza' 
                               ,'$this->me05_t_obs' 
                               ,$this->me05_i_cardapio 
                               ,$this->me05_i_alimento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mer_modpreparo ($this->me05_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mer_modpreparo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mer_modpreparo ($this->me05_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me05_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me05_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12770,'$this->me05_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2237,12770,'','".AddSlashes(pg_result($resaco,0,'me05_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2237,12771,'','".AddSlashes(pg_result($resaco,0,'me05_f_porcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2237,12772,'','".AddSlashes(pg_result($resaco,0,'me05_c_natureza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2237,12774,'','".AddSlashes(pg_result($resaco,0,'me05_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2237,12775,'','".AddSlashes(pg_result($resaco,0,'me05_i_cardapio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2237,17408,'','".AddSlashes(pg_result($resaco,0,'me05_i_alimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me05_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_modpreparo set ";
     $virgula = "";
     if(trim($this->me05_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me05_i_codigo"])){ 
       $sql  .= $virgula." me05_i_codigo = $this->me05_i_codigo ";
       $virgula = ",";
       if(trim($this->me05_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "me05_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me05_f_porcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me05_f_porcao"])){ 
       $sql  .= $virgula." me05_f_porcao = $this->me05_f_porcao ";
       $virgula = ",";
       if(trim($this->me05_f_porcao) == null ){ 
         $this->erro_sql = " Campo Porção nao Informado.";
         $this->erro_campo = "me05_f_porcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me05_c_natureza)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me05_c_natureza"])){ 
       $sql  .= $virgula." me05_c_natureza = '$this->me05_c_natureza' ";
       $virgula = ",";
       if(trim($this->me05_c_natureza) == null ){ 
         $this->erro_sql = " Campo Natureza nao Informado.";
         $this->erro_campo = "me05_c_natureza";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me05_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me05_t_obs"])){ 
       $sql  .= $virgula." me05_t_obs = '$this->me05_t_obs' ";
       $virgula = ",";
       if(trim($this->me05_t_obs) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "me05_t_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me05_i_cardapio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me05_i_cardapio"])){ 
       $sql  .= $virgula." me05_i_cardapio = $this->me05_i_cardapio ";
       $virgula = ",";
       if(trim($this->me05_i_cardapio) == null ){ 
         $this->erro_sql = " Campo Refeição nao Informado.";
         $this->erro_campo = "me05_i_cardapio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me05_i_alimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me05_i_alimento"])){ 
       $sql  .= $virgula." me05_i_alimento = $this->me05_i_alimento ";
       $virgula = ",";
       if(trim($this->me05_i_alimento) == null ){ 
         $this->erro_sql = " Campo Alimento nao Informado.";
         $this->erro_campo = "me05_i_alimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me05_i_codigo!=null){
       $sql .= " me05_i_codigo = $this->me05_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me05_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12770,'$this->me05_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me05_i_codigo"]) || $this->me05_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2237,12770,'".AddSlashes(pg_result($resaco,$conresaco,'me05_i_codigo'))."','$this->me05_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me05_f_porcao"]) || $this->me05_f_porcao != "")
           $resac = db_query("insert into db_acount values($acount,2237,12771,'".AddSlashes(pg_result($resaco,$conresaco,'me05_f_porcao'))."','$this->me05_f_porcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me05_c_natureza"]) || $this->me05_c_natureza != "")
           $resac = db_query("insert into db_acount values($acount,2237,12772,'".AddSlashes(pg_result($resaco,$conresaco,'me05_c_natureza'))."','$this->me05_c_natureza',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me05_t_obs"]) || $this->me05_t_obs != "")
           $resac = db_query("insert into db_acount values($acount,2237,12774,'".AddSlashes(pg_result($resaco,$conresaco,'me05_t_obs'))."','$this->me05_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me05_i_cardapio"]) || $this->me05_i_cardapio != "")
           $resac = db_query("insert into db_acount values($acount,2237,12775,'".AddSlashes(pg_result($resaco,$conresaco,'me05_i_cardapio'))."','$this->me05_i_cardapio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me05_i_alimento"]) || $this->me05_i_alimento != "")
           $resac = db_query("insert into db_acount values($acount,2237,17408,'".AddSlashes(pg_result($resaco,$conresaco,'me05_i_alimento'))."','$this->me05_i_alimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_modpreparo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me05_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_modpreparo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me05_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me05_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me05_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me05_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12770,'$me05_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2237,12770,'','".AddSlashes(pg_result($resaco,$iresaco,'me05_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2237,12771,'','".AddSlashes(pg_result($resaco,$iresaco,'me05_f_porcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2237,12772,'','".AddSlashes(pg_result($resaco,$iresaco,'me05_c_natureza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2237,12774,'','".AddSlashes(pg_result($resaco,$iresaco,'me05_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2237,12775,'','".AddSlashes(pg_result($resaco,$iresaco,'me05_i_cardapio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2237,17408,'','".AddSlashes(pg_result($resaco,$iresaco,'me05_i_alimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_modpreparo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me05_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me05_i_codigo = $me05_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_modpreparo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me05_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_modpreparo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me05_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me05_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_modpreparo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me05_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_modpreparo ";
     $sql .= "      inner join mer_cardapio  on  mer_cardapio.me01_i_codigo = mer_modpreparo.me05_i_cardapio";
     $sql .= "      inner join mer_tipocardapio  on  mer_tipocardapio.me27_i_codigo = mer_cardapio.me01_i_tipocardapio";
     $sql .= "      inner join mer_alimento  on  mer_alimento.me35_i_codigo = mer_modpreparo.me05_i_alimento";     
     $sql2 = "";
     if($dbwhere==""){
       if($me05_i_codigo!=null ){
         $sql2 .= " where mer_modpreparo.me05_i_codigo = $me05_i_codigo "; 
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
   function sql_query_file ( $me05_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_modpreparo ";
     $sql2 = "";
     if($dbwhere==""){
       if($me05_i_codigo!=null ){
         $sql2 .= " where mer_modpreparo.me05_i_codigo = $me05_i_codigo "; 
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