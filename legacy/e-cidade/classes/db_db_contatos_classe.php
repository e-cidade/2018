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

//MODULO: agenda
//CLASSE DA ENTIDADE db_contatos
class cl_db_contatos { 
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
   var $g01_id = 0; 
   var $g01_tipocon = 0; 
   var $g01_organizacao = null; 
   var $g01_nome = null; 
   var $g01_rua = null; 
   var $g01_bairro = null; 
   var $g01_cidade = null; 
   var $g01_uf = null; 
   var $g01_cep = null; 
   var $g01_telef = null; 
   var $g01_fax = null; 
   var $g01_celular = null; 
   var $g01_obs = null; 
   var $g01_email = null; 
   var $g01_site = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 g01_id = int8 = Sequencia 
                 g01_tipocon = int4 = Tipo de Contato 
                 g01_organizacao = varchar(50) = Organização 
                 g01_nome = varchar(40) = Nome do Contato 
                 g01_rua = varchar(50) = Rua 
                 g01_bairro = varchar(40) = Bairro 
                 g01_cidade = varchar(50) = Cidade 
                 g01_uf = char(2) = UF 
                 g01_cep = varchar(12) = CEP 
                 g01_telef = varchar(12) = Telefone 
                 g01_fax = varchar(12) = FAX 
                 g01_celular = varchar(12) = Celular 
                 g01_obs = text = Observação 
                 g01_email = varchar(40) = Email 
                 g01_site = varchar(40) = Site 
                 ";
   //funcao construtor da classe 
   function cl_db_contatos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_contatos"); 
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
       $this->g01_id = ($this->g01_id == ""?@$GLOBALS["HTTP_POST_VARS"]["g01_id"]:$this->g01_id);
       $this->g01_tipocon = ($this->g01_tipocon == ""?@$GLOBALS["HTTP_POST_VARS"]["g01_tipocon"]:$this->g01_tipocon);
       $this->g01_organizacao = ($this->g01_organizacao == ""?@$GLOBALS["HTTP_POST_VARS"]["g01_organizacao"]:$this->g01_organizacao);
       $this->g01_nome = ($this->g01_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["g01_nome"]:$this->g01_nome);
       $this->g01_rua = ($this->g01_rua == ""?@$GLOBALS["HTTP_POST_VARS"]["g01_rua"]:$this->g01_rua);
       $this->g01_bairro = ($this->g01_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["g01_bairro"]:$this->g01_bairro);
       $this->g01_cidade = ($this->g01_cidade == ""?@$GLOBALS["HTTP_POST_VARS"]["g01_cidade"]:$this->g01_cidade);
       $this->g01_uf = ($this->g01_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["g01_uf"]:$this->g01_uf);
       $this->g01_cep = ($this->g01_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["g01_cep"]:$this->g01_cep);
       $this->g01_telef = ($this->g01_telef == ""?@$GLOBALS["HTTP_POST_VARS"]["g01_telef"]:$this->g01_telef);
       $this->g01_fax = ($this->g01_fax == ""?@$GLOBALS["HTTP_POST_VARS"]["g01_fax"]:$this->g01_fax);
       $this->g01_celular = ($this->g01_celular == ""?@$GLOBALS["HTTP_POST_VARS"]["g01_celular"]:$this->g01_celular);
       $this->g01_obs = ($this->g01_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["g01_obs"]:$this->g01_obs);
       $this->g01_email = ($this->g01_email == ""?@$GLOBALS["HTTP_POST_VARS"]["g01_email"]:$this->g01_email);
       $this->g01_site = ($this->g01_site == ""?@$GLOBALS["HTTP_POST_VARS"]["g01_site"]:$this->g01_site);
     }else{
       $this->g01_id = ($this->g01_id == ""?@$GLOBALS["HTTP_POST_VARS"]["g01_id"]:$this->g01_id);
     }
   }
   // funcao para inclusao
   function incluir ($g01_id){ 
      $this->atualizacampos();
     if($this->g01_tipocon == null ){ 
       $this->erro_sql = " Campo Tipo de Contato nao Informado.";
       $this->erro_campo = "g01_tipocon";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g01_nome == null ){ 
       $this->erro_sql = " Campo Nome do Contato nao Informado.";
       $this->erro_campo = "g01_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($g01_id == "" || $g01_id == null ){
       $result = db_query("select nextval('db_contatos_g01_id_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_contatos_g01_id_seq do campo: g01_id"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->g01_id = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_contatos_g01_id_seq");
       if(($result != false) && (pg_result($result,0,0) < $g01_id)){
         $this->erro_sql = " Campo g01_id maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->g01_id = $g01_id; 
       }
     }
     if(($this->g01_id == null) || ($this->g01_id == "") ){ 
       $this->erro_sql = " Campo g01_id nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_contatos(
                                       g01_id 
                                      ,g01_tipocon 
                                      ,g01_organizacao 
                                      ,g01_nome 
                                      ,g01_rua 
                                      ,g01_bairro 
                                      ,g01_cidade 
                                      ,g01_uf 
                                      ,g01_cep 
                                      ,g01_telef 
                                      ,g01_fax 
                                      ,g01_celular 
                                      ,g01_obs 
                                      ,g01_email 
                                      ,g01_site 
                       )
                values (
                                $this->g01_id 
                               ,$this->g01_tipocon 
                               ,'$this->g01_organizacao' 
                               ,'$this->g01_nome' 
                               ,'$this->g01_rua' 
                               ,'$this->g01_bairro' 
                               ,'$this->g01_cidade' 
                               ,'$this->g01_uf' 
                               ,'$this->g01_cep' 
                               ,'$this->g01_telef' 
                               ,'$this->g01_fax' 
                               ,'$this->g01_celular' 
                               ,'$this->g01_obs' 
                               ,'$this->g01_email' 
                               ,'$this->g01_site' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Contados da Agenda ($this->g01_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Contados da Agenda já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Contados da Agenda ($this->g01_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->g01_id;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->g01_id));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2523,'$this->g01_id','I')");
       $resac = db_query("insert into db_acount values($acount,413,2523,'','".AddSlashes(pg_result($resaco,0,'g01_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,413,7855,'','".AddSlashes(pg_result($resaco,0,'g01_tipocon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,413,2512,'','".AddSlashes(pg_result($resaco,0,'g01_organizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,413,2524,'','".AddSlashes(pg_result($resaco,0,'g01_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,413,2518,'','".AddSlashes(pg_result($resaco,0,'g01_rua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,413,2525,'','".AddSlashes(pg_result($resaco,0,'g01_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,413,2519,'','".AddSlashes(pg_result($resaco,0,'g01_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,413,2526,'','".AddSlashes(pg_result($resaco,0,'g01_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,413,2527,'','".AddSlashes(pg_result($resaco,0,'g01_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,413,2520,'','".AddSlashes(pg_result($resaco,0,'g01_telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,413,2528,'','".AddSlashes(pg_result($resaco,0,'g01_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,413,2521,'','".AddSlashes(pg_result($resaco,0,'g01_celular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,413,2529,'','".AddSlashes(pg_result($resaco,0,'g01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,413,2530,'','".AddSlashes(pg_result($resaco,0,'g01_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,413,2522,'','".AddSlashes(pg_result($resaco,0,'g01_site'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($g01_id=null) { 
      $this->atualizacampos();
     $sql = " update db_contatos set ";
     $virgula = "";
     if(trim($this->g01_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g01_id"])){ 
       $sql  .= $virgula." g01_id = $this->g01_id ";
       $virgula = ",";
       if(trim($this->g01_id) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "g01_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g01_tipocon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g01_tipocon"])){ 
       $sql  .= $virgula." g01_tipocon = $this->g01_tipocon ";
       $virgula = ",";
       if(trim($this->g01_tipocon) == null ){ 
         $this->erro_sql = " Campo Tipo de Contato nao Informado.";
         $this->erro_campo = "g01_tipocon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g01_organizacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g01_organizacao"])){ 
       $sql  .= $virgula." g01_organizacao = '$this->g01_organizacao' ";
       $virgula = ",";
     }
     if(trim($this->g01_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g01_nome"])){ 
       $sql  .= $virgula." g01_nome = '$this->g01_nome' ";
       $virgula = ",";
       if(trim($this->g01_nome) == null ){ 
         $this->erro_sql = " Campo Nome do Contato nao Informado.";
         $this->erro_campo = "g01_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g01_rua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g01_rua"])){ 
       $sql  .= $virgula." g01_rua = '$this->g01_rua' ";
       $virgula = ",";
     }
     if(trim($this->g01_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g01_bairro"])){ 
       $sql  .= $virgula." g01_bairro = '$this->g01_bairro' ";
       $virgula = ",";
     }
     if(trim($this->g01_cidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g01_cidade"])){ 
       $sql  .= $virgula." g01_cidade = '$this->g01_cidade' ";
       $virgula = ",";
     }
     if(trim($this->g01_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g01_uf"])){ 
       $sql  .= $virgula." g01_uf = '$this->g01_uf' ";
       $virgula = ",";
     }
     if(trim($this->g01_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g01_cep"])){ 
       $sql  .= $virgula." g01_cep = '$this->g01_cep' ";
       $virgula = ",";
     }
     if(trim($this->g01_telef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g01_telef"])){ 
       $sql  .= $virgula." g01_telef = '$this->g01_telef' ";
       $virgula = ",";
     }
     if(trim($this->g01_fax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g01_fax"])){ 
       $sql  .= $virgula." g01_fax = '$this->g01_fax' ";
       $virgula = ",";
     }
     if(trim($this->g01_celular)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g01_celular"])){ 
       $sql  .= $virgula." g01_celular = '$this->g01_celular' ";
       $virgula = ",";
     }
     if(trim($this->g01_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g01_obs"])){ 
       $sql  .= $virgula." g01_obs = '$this->g01_obs' ";
       $virgula = ",";
     }
     if(trim($this->g01_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g01_email"])){ 
       $sql  .= $virgula." g01_email = '$this->g01_email' ";
       $virgula = ",";
     }
     if(trim($this->g01_site)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g01_site"])){ 
       $sql  .= $virgula." g01_site = '$this->g01_site' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($g01_id!=null){
       $sql .= " g01_id = $this->g01_id";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->g01_id));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2523,'$this->g01_id','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g01_id"]))
           $resac = db_query("insert into db_acount values($acount,413,2523,'".AddSlashes(pg_result($resaco,$conresaco,'g01_id'))."','$this->g01_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g01_tipocon"]))
           $resac = db_query("insert into db_acount values($acount,413,7855,'".AddSlashes(pg_result($resaco,$conresaco,'g01_tipocon'))."','$this->g01_tipocon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g01_organizacao"]))
           $resac = db_query("insert into db_acount values($acount,413,2512,'".AddSlashes(pg_result($resaco,$conresaco,'g01_organizacao'))."','$this->g01_organizacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g01_nome"]))
           $resac = db_query("insert into db_acount values($acount,413,2524,'".AddSlashes(pg_result($resaco,$conresaco,'g01_nome'))."','$this->g01_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g01_rua"]))
           $resac = db_query("insert into db_acount values($acount,413,2518,'".AddSlashes(pg_result($resaco,$conresaco,'g01_rua'))."','$this->g01_rua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g01_bairro"]))
           $resac = db_query("insert into db_acount values($acount,413,2525,'".AddSlashes(pg_result($resaco,$conresaco,'g01_bairro'))."','$this->g01_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g01_cidade"]))
           $resac = db_query("insert into db_acount values($acount,413,2519,'".AddSlashes(pg_result($resaco,$conresaco,'g01_cidade'))."','$this->g01_cidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g01_uf"]))
           $resac = db_query("insert into db_acount values($acount,413,2526,'".AddSlashes(pg_result($resaco,$conresaco,'g01_uf'))."','$this->g01_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g01_cep"]))
           $resac = db_query("insert into db_acount values($acount,413,2527,'".AddSlashes(pg_result($resaco,$conresaco,'g01_cep'))."','$this->g01_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g01_telef"]))
           $resac = db_query("insert into db_acount values($acount,413,2520,'".AddSlashes(pg_result($resaco,$conresaco,'g01_telef'))."','$this->g01_telef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g01_fax"]))
           $resac = db_query("insert into db_acount values($acount,413,2528,'".AddSlashes(pg_result($resaco,$conresaco,'g01_fax'))."','$this->g01_fax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g01_celular"]))
           $resac = db_query("insert into db_acount values($acount,413,2521,'".AddSlashes(pg_result($resaco,$conresaco,'g01_celular'))."','$this->g01_celular',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g01_obs"]))
           $resac = db_query("insert into db_acount values($acount,413,2529,'".AddSlashes(pg_result($resaco,$conresaco,'g01_obs'))."','$this->g01_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g01_email"]))
           $resac = db_query("insert into db_acount values($acount,413,2530,'".AddSlashes(pg_result($resaco,$conresaco,'g01_email'))."','$this->g01_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g01_site"]))
           $resac = db_query("insert into db_acount values($acount,413,2522,'".AddSlashes(pg_result($resaco,$conresaco,'g01_site'))."','$this->g01_site',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contados da Agenda nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->g01_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contados da Agenda nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->g01_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->g01_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($g01_id=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($g01_id));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2523,'$g01_id','E')");
         $resac = db_query("insert into db_acount values($acount,413,2523,'','".AddSlashes(pg_result($resaco,$iresaco,'g01_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,413,7855,'','".AddSlashes(pg_result($resaco,$iresaco,'g01_tipocon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,413,2512,'','".AddSlashes(pg_result($resaco,$iresaco,'g01_organizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,413,2524,'','".AddSlashes(pg_result($resaco,$iresaco,'g01_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,413,2518,'','".AddSlashes(pg_result($resaco,$iresaco,'g01_rua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,413,2525,'','".AddSlashes(pg_result($resaco,$iresaco,'g01_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,413,2519,'','".AddSlashes(pg_result($resaco,$iresaco,'g01_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,413,2526,'','".AddSlashes(pg_result($resaco,$iresaco,'g01_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,413,2527,'','".AddSlashes(pg_result($resaco,$iresaco,'g01_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,413,2520,'','".AddSlashes(pg_result($resaco,$iresaco,'g01_telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,413,2528,'','".AddSlashes(pg_result($resaco,$iresaco,'g01_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,413,2521,'','".AddSlashes(pg_result($resaco,$iresaco,'g01_celular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,413,2529,'','".AddSlashes(pg_result($resaco,$iresaco,'g01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,413,2530,'','".AddSlashes(pg_result($resaco,$iresaco,'g01_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,413,2522,'','".AddSlashes(pg_result($resaco,$iresaco,'g01_site'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_contatos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($g01_id != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " g01_id = $g01_id ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contados da Agenda nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$g01_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contados da Agenda nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$g01_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$g01_id;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_contatos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $g01_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_contatos ";
     $sql .= "      inner join db_contatostipo  on  db_contatostipo.g02_tipocon = db_contatos.g01_tipocon";
     $sql2 = "";
     if($dbwhere==""){
       if($g01_id!=null ){
         $sql2 .= " where db_contatos.g01_id = $g01_id "; 
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
   function sql_query_file ( $g01_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_contatos ";
     $sql2 = "";
     if($dbwhere==""){
       if($g01_id!=null ){
         $sql2 .= " where db_contatos.g01_id = $g01_id "; 
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