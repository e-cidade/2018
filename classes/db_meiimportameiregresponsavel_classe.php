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
//CLASSE DA ENTIDADE meiimportameiregresponsavel
class cl_meiimportameiregresponsavel { 
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
   var $q108_sequencial = 0; 
   var $q108_municipio = null; 
   var $q108_nome = null; 
   var $q108_cpf = null; 
   var $q108_tipologradouro = null; 
   var $q108_logradouro = null; 
   var $q108_numero = null; 
   var $q108_complemento = null; 
   var $q108_bairro = null; 
   var $q108_cep = null; 
   var $q108_uf = null; 
   var $q108_telefone = null; 
   var $q108_fax = null; 
   var $q108_email = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q108_sequencial = int4 = Sequencial 
                 q108_municipio = varchar(40) = Municipio 
                 q108_nome = varchar(60) = Nome 
                 q108_cpf = varchar(11) = Cpf 
                 q108_tipologradouro = varchar(6) = Tipo Logradouro 
                 q108_logradouro = varchar(60) = Logradouro 
                 q108_numero = varchar(6) = Número 
                 q108_complemento = varchar(156) = Complemento 
                 q108_bairro = varchar(50) = Bairro 
                 q108_cep = varchar(8) = CEP 
                 q108_uf = varchar(2) = UF 
                 q108_telefone = varchar(15) = Telefone 
                 q108_fax = varchar(15) = Fax 
                 q108_email = varchar(115) = Email 
                 ";
   //funcao construtor da classe 
   function cl_meiimportameiregresponsavel() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("meiimportameiregresponsavel"); 
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
       $this->q108_sequencial = ($this->q108_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q108_sequencial"]:$this->q108_sequencial);
       $this->q108_municipio = ($this->q108_municipio == ""?@$GLOBALS["HTTP_POST_VARS"]["q108_municipio"]:$this->q108_municipio);
       $this->q108_nome = ($this->q108_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["q108_nome"]:$this->q108_nome);
       $this->q108_cpf = ($this->q108_cpf == ""?@$GLOBALS["HTTP_POST_VARS"]["q108_cpf"]:$this->q108_cpf);
       $this->q108_tipologradouro = ($this->q108_tipologradouro == ""?@$GLOBALS["HTTP_POST_VARS"]["q108_tipologradouro"]:$this->q108_tipologradouro);
       $this->q108_logradouro = ($this->q108_logradouro == ""?@$GLOBALS["HTTP_POST_VARS"]["q108_logradouro"]:$this->q108_logradouro);
       $this->q108_numero = ($this->q108_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["q108_numero"]:$this->q108_numero);
       $this->q108_complemento = ($this->q108_complemento == ""?@$GLOBALS["HTTP_POST_VARS"]["q108_complemento"]:$this->q108_complemento);
       $this->q108_bairro = ($this->q108_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["q108_bairro"]:$this->q108_bairro);
       $this->q108_cep = ($this->q108_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["q108_cep"]:$this->q108_cep);
       $this->q108_uf = ($this->q108_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["q108_uf"]:$this->q108_uf);
       $this->q108_telefone = ($this->q108_telefone == ""?@$GLOBALS["HTTP_POST_VARS"]["q108_telefone"]:$this->q108_telefone);
       $this->q108_fax = ($this->q108_fax == ""?@$GLOBALS["HTTP_POST_VARS"]["q108_fax"]:$this->q108_fax);
       $this->q108_email = ($this->q108_email == ""?@$GLOBALS["HTTP_POST_VARS"]["q108_email"]:$this->q108_email);
     }else{
       $this->q108_sequencial = ($this->q108_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q108_sequencial"]:$this->q108_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q108_sequencial){ 
      $this->atualizacampos();
     if($this->q108_municipio == null ){ 
       $this->q108_municipio = "0";
     }
     if($q108_sequencial == "" || $q108_sequencial == null ){
       $result = db_query("select nextval('meiimportameiregresponsavel_q108_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: meiimportameiregresponsavel_q108_sequencial_seq do campo: q108_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q108_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from meiimportameiregresponsavel_q108_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q108_sequencial)){
         $this->erro_sql = " Campo q108_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q108_sequencial = $q108_sequencial; 
       }
     }
     if(($this->q108_sequencial == null) || ($this->q108_sequencial == "") ){ 
       $this->erro_sql = " Campo q108_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into meiimportameiregresponsavel(
                                       q108_sequencial 
                                      ,q108_municipio 
                                      ,q108_nome 
                                      ,q108_cpf 
                                      ,q108_tipologradouro 
                                      ,q108_logradouro 
                                      ,q108_numero 
                                      ,q108_complemento 
                                      ,q108_bairro 
                                      ,q108_cep 
                                      ,q108_uf 
                                      ,q108_telefone 
                                      ,q108_fax 
                                      ,q108_email 
                       )
                values (
                                $this->q108_sequencial 
                               ,'$this->q108_municipio' 
                               ,'$this->q108_nome' 
                               ,'$this->q108_cpf' 
                               ,'$this->q108_tipologradouro' 
                               ,'$this->q108_logradouro' 
                               ,'$this->q108_numero' 
                               ,'$this->q108_complemento' 
                               ,'$this->q108_bairro' 
                               ,'$this->q108_cep' 
                               ,'$this->q108_uf' 
                               ,'$this->q108_telefone' 
                               ,'$this->q108_fax' 
                               ,'$this->q108_email' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Importação do MEI por Resposável ($this->q108_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Importação do MEI por Resposável já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Importação do MEI por Resposável ($this->q108_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q108_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q108_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16275,'$this->q108_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2853,16275,'','".AddSlashes(pg_result($resaco,0,'q108_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2853,16276,'','".AddSlashes(pg_result($resaco,0,'q108_municipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2853,16278,'','".AddSlashes(pg_result($resaco,0,'q108_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2853,16279,'','".AddSlashes(pg_result($resaco,0,'q108_cpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2853,16280,'','".AddSlashes(pg_result($resaco,0,'q108_tipologradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2853,16281,'','".AddSlashes(pg_result($resaco,0,'q108_logradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2853,16282,'','".AddSlashes(pg_result($resaco,0,'q108_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2853,16283,'','".AddSlashes(pg_result($resaco,0,'q108_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2853,16284,'','".AddSlashes(pg_result($resaco,0,'q108_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2853,16285,'','".AddSlashes(pg_result($resaco,0,'q108_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2853,16286,'','".AddSlashes(pg_result($resaco,0,'q108_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2853,16287,'','".AddSlashes(pg_result($resaco,0,'q108_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2853,16288,'','".AddSlashes(pg_result($resaco,0,'q108_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2853,16289,'','".AddSlashes(pg_result($resaco,0,'q108_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q108_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update meiimportameiregresponsavel set ";
     $virgula = "";
     if(trim($this->q108_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q108_sequencial"])){ 
       $sql  .= $virgula." q108_sequencial = $this->q108_sequencial ";
       $virgula = ",";
       if(trim($this->q108_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q108_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q108_municipio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q108_municipio"])){ 
       $sql  .= $virgula." q108_municipio = '$this->q108_municipio' ";
       $virgula = ",";
     }
     if(trim($this->q108_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q108_nome"])){ 
       $sql  .= $virgula." q108_nome = '$this->q108_nome' ";
       $virgula = ",";
     }
     if(trim($this->q108_cpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q108_cpf"])){ 
       $sql  .= $virgula." q108_cpf = '$this->q108_cpf' ";
       $virgula = ",";
     }
     if(trim($this->q108_tipologradouro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q108_tipologradouro"])){ 
       $sql  .= $virgula." q108_tipologradouro = '$this->q108_tipologradouro' ";
       $virgula = ",";
     }
     if(trim($this->q108_logradouro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q108_logradouro"])){ 
       $sql  .= $virgula." q108_logradouro = '$this->q108_logradouro' ";
       $virgula = ",";
     }
     if(trim($this->q108_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q108_numero"])){ 
       $sql  .= $virgula." q108_numero = '$this->q108_numero' ";
       $virgula = ",";
     }
     if(trim($this->q108_complemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q108_complemento"])){ 
       $sql  .= $virgula." q108_complemento = '$this->q108_complemento' ";
       $virgula = ",";
     }
     if(trim($this->q108_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q108_bairro"])){ 
       $sql  .= $virgula." q108_bairro = '$this->q108_bairro' ";
       $virgula = ",";
     }
     if(trim($this->q108_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q108_cep"])){ 
       $sql  .= $virgula." q108_cep = '$this->q108_cep' ";
       $virgula = ",";
     }
     if(trim($this->q108_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q108_uf"])){ 
       $sql  .= $virgula." q108_uf = '$this->q108_uf' ";
       $virgula = ",";
     }
     if(trim($this->q108_telefone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q108_telefone"])){ 
       $sql  .= $virgula." q108_telefone = '$this->q108_telefone' ";
       $virgula = ",";
     }
     if(trim($this->q108_fax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q108_fax"])){ 
       $sql  .= $virgula." q108_fax = '$this->q108_fax' ";
       $virgula = ",";
     }
     if(trim($this->q108_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q108_email"])){ 
       $sql  .= $virgula." q108_email = '$this->q108_email' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q108_sequencial!=null){
       $sql .= " q108_sequencial = $this->q108_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q108_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16275,'$this->q108_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q108_sequencial"]) || $this->q108_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2853,16275,'".AddSlashes(pg_result($resaco,$conresaco,'q108_sequencial'))."','$this->q108_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q108_municipio"]) || $this->q108_municipio != "")
           $resac = db_query("insert into db_acount values($acount,2853,16276,'".AddSlashes(pg_result($resaco,$conresaco,'q108_municipio'))."','$this->q108_municipio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q108_nome"]) || $this->q108_nome != "")
           $resac = db_query("insert into db_acount values($acount,2853,16278,'".AddSlashes(pg_result($resaco,$conresaco,'q108_nome'))."','$this->q108_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q108_cpf"]) || $this->q108_cpf != "")
           $resac = db_query("insert into db_acount values($acount,2853,16279,'".AddSlashes(pg_result($resaco,$conresaco,'q108_cpf'))."','$this->q108_cpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q108_tipologradouro"]) || $this->q108_tipologradouro != "")
           $resac = db_query("insert into db_acount values($acount,2853,16280,'".AddSlashes(pg_result($resaco,$conresaco,'q108_tipologradouro'))."','$this->q108_tipologradouro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q108_logradouro"]) || $this->q108_logradouro != "")
           $resac = db_query("insert into db_acount values($acount,2853,16281,'".AddSlashes(pg_result($resaco,$conresaco,'q108_logradouro'))."','$this->q108_logradouro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q108_numero"]) || $this->q108_numero != "")
           $resac = db_query("insert into db_acount values($acount,2853,16282,'".AddSlashes(pg_result($resaco,$conresaco,'q108_numero'))."','$this->q108_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q108_complemento"]) || $this->q108_complemento != "")
           $resac = db_query("insert into db_acount values($acount,2853,16283,'".AddSlashes(pg_result($resaco,$conresaco,'q108_complemento'))."','$this->q108_complemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q108_bairro"]) || $this->q108_bairro != "")
           $resac = db_query("insert into db_acount values($acount,2853,16284,'".AddSlashes(pg_result($resaco,$conresaco,'q108_bairro'))."','$this->q108_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q108_cep"]) || $this->q108_cep != "")
           $resac = db_query("insert into db_acount values($acount,2853,16285,'".AddSlashes(pg_result($resaco,$conresaco,'q108_cep'))."','$this->q108_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q108_uf"]) || $this->q108_uf != "")
           $resac = db_query("insert into db_acount values($acount,2853,16286,'".AddSlashes(pg_result($resaco,$conresaco,'q108_uf'))."','$this->q108_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q108_telefone"]) || $this->q108_telefone != "")
           $resac = db_query("insert into db_acount values($acount,2853,16287,'".AddSlashes(pg_result($resaco,$conresaco,'q108_telefone'))."','$this->q108_telefone',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q108_fax"]) || $this->q108_fax != "")
           $resac = db_query("insert into db_acount values($acount,2853,16288,'".AddSlashes(pg_result($resaco,$conresaco,'q108_fax'))."','$this->q108_fax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q108_email"]) || $this->q108_email != "")
           $resac = db_query("insert into db_acount values($acount,2853,16289,'".AddSlashes(pg_result($resaco,$conresaco,'q108_email'))."','$this->q108_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Importação do MEI por Resposável nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q108_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Importação do MEI por Resposável nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q108_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q108_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q108_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q108_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16275,'$q108_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2853,16275,'','".AddSlashes(pg_result($resaco,$iresaco,'q108_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2853,16276,'','".AddSlashes(pg_result($resaco,$iresaco,'q108_municipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2853,16278,'','".AddSlashes(pg_result($resaco,$iresaco,'q108_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2853,16279,'','".AddSlashes(pg_result($resaco,$iresaco,'q108_cpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2853,16280,'','".AddSlashes(pg_result($resaco,$iresaco,'q108_tipologradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2853,16281,'','".AddSlashes(pg_result($resaco,$iresaco,'q108_logradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2853,16282,'','".AddSlashes(pg_result($resaco,$iresaco,'q108_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2853,16283,'','".AddSlashes(pg_result($resaco,$iresaco,'q108_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2853,16284,'','".AddSlashes(pg_result($resaco,$iresaco,'q108_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2853,16285,'','".AddSlashes(pg_result($resaco,$iresaco,'q108_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2853,16286,'','".AddSlashes(pg_result($resaco,$iresaco,'q108_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2853,16287,'','".AddSlashes(pg_result($resaco,$iresaco,'q108_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2853,16288,'','".AddSlashes(pg_result($resaco,$iresaco,'q108_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2853,16289,'','".AddSlashes(pg_result($resaco,$iresaco,'q108_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from meiimportameiregresponsavel
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q108_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q108_sequencial = $q108_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Importação do MEI por Resposável nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q108_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Importação do MEI por Resposável nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q108_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q108_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:meiimportameiregresponsavel";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q108_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiimportameiregresponsavel ";
     $sql2 = "";
     if($dbwhere==""){
       if($q108_sequencial!=null ){
         $sql2 .= " where meiimportameiregresponsavel.q108_sequencial = $q108_sequencial "; 
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
   function sql_query_file ( $q108_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiimportameiregresponsavel ";
     $sql2 = "";
     if($dbwhere==""){
       if($q108_sequencial!=null ){
         $sql2 .= " where meiimportameiregresponsavel.q108_sequencial = $q108_sequencial "; 
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