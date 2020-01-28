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
//CLASSE DA ENTIDADE meiimportameiregempresa
class cl_meiimportameiregempresa { 
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
   var $q107_sequencial = 0; 
   var $q107_municipio = null; 
   var $q107_cnpj = null; 
   var $q107_cnpjmatriz = null; 
   var $q107_nome = null; 
   var $q107_capitalsocial = 0; 
   var $q107_nomefantasia = null; 
   var $q107_tipologradouro = null; 
   var $q107_logradouro = null; 
   var $q107_numero = null; 
   var $q107_complemento = null; 
   var $q107_bairro = null; 
   var $q107_uf = null; 
   var $q107_cep = null; 
   var $q107_referencia = null; 
   var $q107_telefone = null; 
   var $q107_telefonecomercial = null; 
   var $q107_fax = null; 
   var $q107_email = null; 
   var $q107_caixapostal = null; 
   var $q107_inscrmei = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q107_sequencial = int4 = Sequencial 
                 q107_municipio = varchar(40) = Municipio 
                 q107_cnpj = varchar(14) = CNPJ MEI 
                 q107_cnpjmatriz = varchar(14) = CNPJ Matriz 
                 q107_nome = varchar(150) = Nome 
                 q107_capitalsocial = float4 = Capital Social 
                 q107_nomefantasia = varchar(55) = Nome Fantasia 
                 q107_tipologradouro = varchar(6) = Tipo Logradouro 
                 q107_logradouro = varchar(60) = Logradouro 
                 q107_numero = varchar(6) = Número 
                 q107_complemento = varchar(150) = Complemento 
                 q107_bairro = varchar(50) = Bairro 
                 q107_uf = varchar(2) = UF 
                 q107_cep = varchar(8) = CEP 
                 q107_referencia = varchar(150) = Referência 
                 q107_telefone = varchar(20) = Telefone 
                 q107_telefonecomercial = varchar(20) = Telefone Comercial 
                 q107_fax = varchar(20) = Fax 
                 q107_email = varchar(115) = Email 
                 q107_caixapostal = varchar(20) = Caixa Postal 
                 q107_inscrmei = bool = Inscrito MEI 
                 ";
   //funcao construtor da classe 
   function cl_meiimportameiregempresa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("meiimportameiregempresa"); 
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
       $this->q107_sequencial = ($this->q107_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_sequencial"]:$this->q107_sequencial);
       $this->q107_municipio = ($this->q107_municipio == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_municipio"]:$this->q107_municipio);
       $this->q107_cnpj = ($this->q107_cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_cnpj"]:$this->q107_cnpj);
       $this->q107_cnpjmatriz = ($this->q107_cnpjmatriz == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_cnpjmatriz"]:$this->q107_cnpjmatriz);
       $this->q107_nome = ($this->q107_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_nome"]:$this->q107_nome);
       $this->q107_capitalsocial = ($this->q107_capitalsocial == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_capitalsocial"]:$this->q107_capitalsocial);
       $this->q107_nomefantasia = ($this->q107_nomefantasia == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_nomefantasia"]:$this->q107_nomefantasia);
       $this->q107_tipologradouro = ($this->q107_tipologradouro == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_tipologradouro"]:$this->q107_tipologradouro);
       $this->q107_logradouro = ($this->q107_logradouro == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_logradouro"]:$this->q107_logradouro);
       $this->q107_numero = ($this->q107_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_numero"]:$this->q107_numero);
       $this->q107_complemento = ($this->q107_complemento == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_complemento"]:$this->q107_complemento);
       $this->q107_bairro = ($this->q107_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_bairro"]:$this->q107_bairro);
       $this->q107_uf = ($this->q107_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_uf"]:$this->q107_uf);
       $this->q107_cep = ($this->q107_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_cep"]:$this->q107_cep);
       $this->q107_referencia = ($this->q107_referencia == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_referencia"]:$this->q107_referencia);
       $this->q107_telefone = ($this->q107_telefone == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_telefone"]:$this->q107_telefone);
       $this->q107_telefonecomercial = ($this->q107_telefonecomercial == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_telefonecomercial"]:$this->q107_telefonecomercial);
       $this->q107_fax = ($this->q107_fax == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_fax"]:$this->q107_fax);
       $this->q107_email = ($this->q107_email == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_email"]:$this->q107_email);
       $this->q107_caixapostal = ($this->q107_caixapostal == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_caixapostal"]:$this->q107_caixapostal);
       $this->q107_inscrmei = ($this->q107_inscrmei == "f"?@$GLOBALS["HTTP_POST_VARS"]["q107_inscrmei"]:$this->q107_inscrmei);
     }else{
       $this->q107_sequencial = ($this->q107_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q107_sequencial"]:$this->q107_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q107_sequencial){ 
      $this->atualizacampos();
     if($this->q107_municipio == null ){ 
       $this->q107_municipio = "0";
     }
     if($this->q107_cnpj == null ){ 
       $this->erro_sql = " Campo CNPJ MEI nao Informado.";
       $this->erro_campo = "q107_cnpj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q107_capitalsocial == null ){ 
       $this->q107_capitalsocial = "0";
     }
     if($this->q107_inscrmei == null ){ 
       $this->erro_sql = " Campo Inscrito MEI nao Informado.";
       $this->erro_campo = "q107_inscrmei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q107_sequencial == "" || $q107_sequencial == null ){
       $result = db_query("select nextval('meiimportameiregempresa_q107_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: meiimportameiregempresa_q107_sequencial_seq do campo: q107_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q107_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from meiimportameiregempresa_q107_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q107_sequencial)){
         $this->erro_sql = " Campo q107_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q107_sequencial = $q107_sequencial; 
       }
     }
     if(($this->q107_sequencial == null) || ($this->q107_sequencial == "") ){ 
       $this->erro_sql = " Campo q107_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into meiimportameiregempresa(
                                       q107_sequencial 
                                      ,q107_municipio 
                                      ,q107_cnpj 
                                      ,q107_cnpjmatriz 
                                      ,q107_nome 
                                      ,q107_capitalsocial 
                                      ,q107_nomefantasia 
                                      ,q107_tipologradouro 
                                      ,q107_logradouro 
                                      ,q107_numero 
                                      ,q107_complemento 
                                      ,q107_bairro 
                                      ,q107_uf 
                                      ,q107_cep 
                                      ,q107_referencia 
                                      ,q107_telefone 
                                      ,q107_telefonecomercial 
                                      ,q107_fax 
                                      ,q107_email 
                                      ,q107_caixapostal 
                                      ,q107_inscrmei 
                       )
                values (
                                $this->q107_sequencial 
                               ,'$this->q107_municipio' 
                               ,'$this->q107_cnpj' 
                               ,'$this->q107_cnpjmatriz' 
                               ,'$this->q107_nome' 
                               ,$this->q107_capitalsocial 
                               ,'$this->q107_nomefantasia' 
                               ,'$this->q107_tipologradouro' 
                               ,'$this->q107_logradouro' 
                               ,'$this->q107_numero' 
                               ,'$this->q107_complemento' 
                               ,'$this->q107_bairro' 
                               ,'$this->q107_uf' 
                               ,'$this->q107_cep' 
                               ,'$this->q107_referencia' 
                               ,'$this->q107_telefone' 
                               ,'$this->q107_telefonecomercial' 
                               ,'$this->q107_fax' 
                               ,'$this->q107_email' 
                               ,'$this->q107_caixapostal' 
                               ,'$this->q107_inscrmei' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Importação do MEI por Empresa ($this->q107_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Importação do MEI por Empresa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Importação do MEI por Empresa ($this->q107_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q107_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q107_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16249,'$this->q107_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2851,16249,'','".AddSlashes(pg_result($resaco,0,'q107_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16250,'','".AddSlashes(pg_result($resaco,0,'q107_municipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16324,'','".AddSlashes(pg_result($resaco,0,'q107_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16252,'','".AddSlashes(pg_result($resaco,0,'q107_cnpjmatriz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16253,'','".AddSlashes(pg_result($resaco,0,'q107_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16254,'','".AddSlashes(pg_result($resaco,0,'q107_capitalsocial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16255,'','".AddSlashes(pg_result($resaco,0,'q107_nomefantasia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16256,'','".AddSlashes(pg_result($resaco,0,'q107_tipologradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16257,'','".AddSlashes(pg_result($resaco,0,'q107_logradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16258,'','".AddSlashes(pg_result($resaco,0,'q107_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16259,'','".AddSlashes(pg_result($resaco,0,'q107_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16260,'','".AddSlashes(pg_result($resaco,0,'q107_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16261,'','".AddSlashes(pg_result($resaco,0,'q107_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16262,'','".AddSlashes(pg_result($resaco,0,'q107_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16263,'','".AddSlashes(pg_result($resaco,0,'q107_referencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16264,'','".AddSlashes(pg_result($resaco,0,'q107_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16265,'','".AddSlashes(pg_result($resaco,0,'q107_telefonecomercial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16266,'','".AddSlashes(pg_result($resaco,0,'q107_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16267,'','".AddSlashes(pg_result($resaco,0,'q107_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16268,'','".AddSlashes(pg_result($resaco,0,'q107_caixapostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2851,16269,'','".AddSlashes(pg_result($resaco,0,'q107_inscrmei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q107_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update meiimportameiregempresa set ";
     $virgula = "";
     if(trim($this->q107_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_sequencial"])){ 
       $sql  .= $virgula." q107_sequencial = $this->q107_sequencial ";
       $virgula = ",";
       if(trim($this->q107_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q107_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q107_municipio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_municipio"])){ 
       $sql  .= $virgula." q107_municipio = '$this->q107_municipio' ";
       $virgula = ",";
     }
     if(trim($this->q107_cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_cnpj"])){ 
       $sql  .= $virgula." q107_cnpj = '$this->q107_cnpj' ";
       $virgula = ",";
       if(trim($this->q107_cnpj) == null ){ 
         $this->erro_sql = " Campo CNPJ MEI nao Informado.";
         $this->erro_campo = "q107_cnpj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q107_cnpjmatriz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_cnpjmatriz"])){ 
       $sql  .= $virgula." q107_cnpjmatriz = '$this->q107_cnpjmatriz' ";
       $virgula = ",";
     }
     if(trim($this->q107_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_nome"])){ 
       $sql  .= $virgula." q107_nome = '$this->q107_nome' ";
       $virgula = ",";
     }
     if(trim($this->q107_capitalsocial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_capitalsocial"])){ 
        if(trim($this->q107_capitalsocial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q107_capitalsocial"])){ 
           $this->q107_capitalsocial = "0" ; 
        } 
       $sql  .= $virgula." q107_capitalsocial = $this->q107_capitalsocial ";
       $virgula = ",";
     }
     if(trim($this->q107_nomefantasia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_nomefantasia"])){ 
       $sql  .= $virgula." q107_nomefantasia = '$this->q107_nomefantasia' ";
       $virgula = ",";
     }
     if(trim($this->q107_tipologradouro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_tipologradouro"])){ 
       $sql  .= $virgula." q107_tipologradouro = '$this->q107_tipologradouro' ";
       $virgula = ",";
     }
     if(trim($this->q107_logradouro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_logradouro"])){ 
       $sql  .= $virgula." q107_logradouro = '$this->q107_logradouro' ";
       $virgula = ",";
     }
     if(trim($this->q107_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_numero"])){ 
       $sql  .= $virgula." q107_numero = '$this->q107_numero' ";
       $virgula = ",";
     }
     if(trim($this->q107_complemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_complemento"])){ 
       $sql  .= $virgula." q107_complemento = '$this->q107_complemento' ";
       $virgula = ",";
     }
     if(trim($this->q107_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_bairro"])){ 
       $sql  .= $virgula." q107_bairro = '$this->q107_bairro' ";
       $virgula = ",";
     }
     if(trim($this->q107_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_uf"])){ 
       $sql  .= $virgula." q107_uf = '$this->q107_uf' ";
       $virgula = ",";
     }
     if(trim($this->q107_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_cep"])){ 
       $sql  .= $virgula." q107_cep = '$this->q107_cep' ";
       $virgula = ",";
     }
     if(trim($this->q107_referencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_referencia"])){ 
       $sql  .= $virgula." q107_referencia = '$this->q107_referencia' ";
       $virgula = ",";
     }
     if(trim($this->q107_telefone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_telefone"])){ 
       $sql  .= $virgula." q107_telefone = '$this->q107_telefone' ";
       $virgula = ",";
     }
     if(trim($this->q107_telefonecomercial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_telefonecomercial"])){ 
       $sql  .= $virgula." q107_telefonecomercial = '$this->q107_telefonecomercial' ";
       $virgula = ",";
     }
     if(trim($this->q107_fax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_fax"])){ 
       $sql  .= $virgula." q107_fax = '$this->q107_fax' ";
       $virgula = ",";
     }
     if(trim($this->q107_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_email"])){ 
       $sql  .= $virgula." q107_email = '$this->q107_email' ";
       $virgula = ",";
     }
     if(trim($this->q107_caixapostal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_caixapostal"])){ 
       $sql  .= $virgula." q107_caixapostal = '$this->q107_caixapostal' ";
       $virgula = ",";
     }
     if(trim($this->q107_inscrmei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q107_inscrmei"])){ 
       $sql  .= $virgula." q107_inscrmei = '$this->q107_inscrmei' ";
       $virgula = ",";
       if(trim($this->q107_inscrmei) == null ){ 
         $this->erro_sql = " Campo Inscrito MEI nao Informado.";
         $this->erro_campo = "q107_inscrmei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q107_sequencial!=null){
       $sql .= " q107_sequencial = $this->q107_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q107_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16249,'$this->q107_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_sequencial"]) || $this->q107_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2851,16249,'".AddSlashes(pg_result($resaco,$conresaco,'q107_sequencial'))."','$this->q107_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_municipio"]) || $this->q107_municipio != "")
           $resac = db_query("insert into db_acount values($acount,2851,16250,'".AddSlashes(pg_result($resaco,$conresaco,'q107_municipio'))."','$this->q107_municipio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_cnpj"]) || $this->q107_cnpj != "")
           $resac = db_query("insert into db_acount values($acount,2851,16324,'".AddSlashes(pg_result($resaco,$conresaco,'q107_cnpj'))."','$this->q107_cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_cnpjmatriz"]) || $this->q107_cnpjmatriz != "")
           $resac = db_query("insert into db_acount values($acount,2851,16252,'".AddSlashes(pg_result($resaco,$conresaco,'q107_cnpjmatriz'))."','$this->q107_cnpjmatriz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_nome"]) || $this->q107_nome != "")
           $resac = db_query("insert into db_acount values($acount,2851,16253,'".AddSlashes(pg_result($resaco,$conresaco,'q107_nome'))."','$this->q107_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_capitalsocial"]) || $this->q107_capitalsocial != "")
           $resac = db_query("insert into db_acount values($acount,2851,16254,'".AddSlashes(pg_result($resaco,$conresaco,'q107_capitalsocial'))."','$this->q107_capitalsocial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_nomefantasia"]) || $this->q107_nomefantasia != "")
           $resac = db_query("insert into db_acount values($acount,2851,16255,'".AddSlashes(pg_result($resaco,$conresaco,'q107_nomefantasia'))."','$this->q107_nomefantasia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_tipologradouro"]) || $this->q107_tipologradouro != "")
           $resac = db_query("insert into db_acount values($acount,2851,16256,'".AddSlashes(pg_result($resaco,$conresaco,'q107_tipologradouro'))."','$this->q107_tipologradouro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_logradouro"]) || $this->q107_logradouro != "")
           $resac = db_query("insert into db_acount values($acount,2851,16257,'".AddSlashes(pg_result($resaco,$conresaco,'q107_logradouro'))."','$this->q107_logradouro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_numero"]) || $this->q107_numero != "")
           $resac = db_query("insert into db_acount values($acount,2851,16258,'".AddSlashes(pg_result($resaco,$conresaco,'q107_numero'))."','$this->q107_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_complemento"]) || $this->q107_complemento != "")
           $resac = db_query("insert into db_acount values($acount,2851,16259,'".AddSlashes(pg_result($resaco,$conresaco,'q107_complemento'))."','$this->q107_complemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_bairro"]) || $this->q107_bairro != "")
           $resac = db_query("insert into db_acount values($acount,2851,16260,'".AddSlashes(pg_result($resaco,$conresaco,'q107_bairro'))."','$this->q107_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_uf"]) || $this->q107_uf != "")
           $resac = db_query("insert into db_acount values($acount,2851,16261,'".AddSlashes(pg_result($resaco,$conresaco,'q107_uf'))."','$this->q107_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_cep"]) || $this->q107_cep != "")
           $resac = db_query("insert into db_acount values($acount,2851,16262,'".AddSlashes(pg_result($resaco,$conresaco,'q107_cep'))."','$this->q107_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_referencia"]) || $this->q107_referencia != "")
           $resac = db_query("insert into db_acount values($acount,2851,16263,'".AddSlashes(pg_result($resaco,$conresaco,'q107_referencia'))."','$this->q107_referencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_telefone"]) || $this->q107_telefone != "")
           $resac = db_query("insert into db_acount values($acount,2851,16264,'".AddSlashes(pg_result($resaco,$conresaco,'q107_telefone'))."','$this->q107_telefone',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_telefonecomercial"]) || $this->q107_telefonecomercial != "")
           $resac = db_query("insert into db_acount values($acount,2851,16265,'".AddSlashes(pg_result($resaco,$conresaco,'q107_telefonecomercial'))."','$this->q107_telefonecomercial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_fax"]) || $this->q107_fax != "")
           $resac = db_query("insert into db_acount values($acount,2851,16266,'".AddSlashes(pg_result($resaco,$conresaco,'q107_fax'))."','$this->q107_fax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_email"]) || $this->q107_email != "")
           $resac = db_query("insert into db_acount values($acount,2851,16267,'".AddSlashes(pg_result($resaco,$conresaco,'q107_email'))."','$this->q107_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_caixapostal"]) || $this->q107_caixapostal != "")
           $resac = db_query("insert into db_acount values($acount,2851,16268,'".AddSlashes(pg_result($resaco,$conresaco,'q107_caixapostal'))."','$this->q107_caixapostal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q107_inscrmei"]) || $this->q107_inscrmei != "")
           $resac = db_query("insert into db_acount values($acount,2851,16269,'".AddSlashes(pg_result($resaco,$conresaco,'q107_inscrmei'))."','$this->q107_inscrmei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Importação do MEI por Empresa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q107_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Importação do MEI por Empresa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q107_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q107_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q107_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q107_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16249,'$q107_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2851,16249,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16250,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_municipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16324,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16252,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_cnpjmatriz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16253,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16254,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_capitalsocial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16255,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_nomefantasia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16256,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_tipologradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16257,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_logradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16258,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16259,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16260,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16261,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16262,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16263,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_referencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16264,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16265,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_telefonecomercial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16266,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16267,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16268,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_caixapostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2851,16269,'','".AddSlashes(pg_result($resaco,$iresaco,'q107_inscrmei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from meiimportameiregempresa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q107_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q107_sequencial = $q107_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Importação do MEI por Empresa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q107_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Importação do MEI por Empresa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q107_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q107_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:meiimportameiregempresa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q107_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiimportameiregempresa ";
     $sql2 = "";
     if($dbwhere==""){
       if($q107_sequencial!=null ){
         $sql2 .= " where meiimportameiregempresa.q107_sequencial = $q107_sequencial "; 
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
   function sql_query_file ( $q107_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiimportameiregempresa ";
     $sql2 = "";
     if($dbwhere==""){
       if($q107_sequencial!=null ){
         $sql2 .= " where meiimportameiregempresa.q107_sequencial = $q107_sequencial "; 
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