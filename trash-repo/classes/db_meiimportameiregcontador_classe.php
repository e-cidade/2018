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
//CLASSE DA ENTIDADE meiimportameiregcontador
class cl_meiimportameiregcontador { 
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
   var $q109_sequencial = 0; 
   var $q109_municipio = null; 
   var $q109_ufcrc = null; 
   var $q109_codigocrc = null; 
   var $q109_datacrc_dia = null; 
   var $q109_datacrc_mes = null; 
   var $q109_datacrc_ano = null; 
   var $q109_datacrc = null; 
   var $q109_cnpjcpf = null; 
   var $q109_nome = null; 
   var $q109_tipologradouro = null; 
   var $q109_logradouro = null; 
   var $q109_numero = null; 
   var $q109_complemento = null; 
   var $q109_bairro = null; 
   var $q109_cep = null; 
   var $q109_uf = null; 
   var $q109_telefone = null; 
   var $q109_fax = null; 
   var $q109_email = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q109_sequencial = int4 = Sequencial 
                 q109_municipio = varchar(40) = Municipio 
                 q109_ufcrc = varchar(2) = UF CRC 
                 q109_codigocrc = varchar(6) = Código CRC 
                 q109_datacrc = date = Data CRC 
                 q109_cnpjcpf = varchar(14) = CNPJ/CPF 
                 q109_nome = varchar(60) = Nome 
                 q109_tipologradouro = varchar(6) = Tipo Logradouro 
                 q109_logradouro = varchar(60) = Logradouro 
                 q109_numero = varchar(6) = Número 
                 q109_complemento = varchar(156) = Complemento 
                 q109_bairro = varchar(50) = Bairro 
                 q109_cep = varchar(8) = CEP 
                 q109_uf = varchar(2) = UF 
                 q109_telefone = varchar(15) = Telefone 
                 q109_fax = varchar(15) = Fax 
                 q109_email = varchar(115) = Email 
                 ";
   //funcao construtor da classe 
   function cl_meiimportameiregcontador() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("meiimportameiregcontador"); 
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
       $this->q109_sequencial = ($this->q109_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_sequencial"]:$this->q109_sequencial);
       $this->q109_municipio = ($this->q109_municipio == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_municipio"]:$this->q109_municipio);
       $this->q109_ufcrc = ($this->q109_ufcrc == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_ufcrc"]:$this->q109_ufcrc);
       $this->q109_codigocrc = ($this->q109_codigocrc == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_codigocrc"]:$this->q109_codigocrc);
       if($this->q109_datacrc == ""){
         $this->q109_datacrc_dia = ($this->q109_datacrc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_datacrc_dia"]:$this->q109_datacrc_dia);
         $this->q109_datacrc_mes = ($this->q109_datacrc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_datacrc_mes"]:$this->q109_datacrc_mes);
         $this->q109_datacrc_ano = ($this->q109_datacrc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_datacrc_ano"]:$this->q109_datacrc_ano);
         if($this->q109_datacrc_dia != ""){
            $this->q109_datacrc = $this->q109_datacrc_ano."-".$this->q109_datacrc_mes."-".$this->q109_datacrc_dia;
         }
       }
       $this->q109_cnpjcpf = ($this->q109_cnpjcpf == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_cnpjcpf"]:$this->q109_cnpjcpf);
       $this->q109_nome = ($this->q109_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_nome"]:$this->q109_nome);
       $this->q109_tipologradouro = ($this->q109_tipologradouro == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_tipologradouro"]:$this->q109_tipologradouro);
       $this->q109_logradouro = ($this->q109_logradouro == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_logradouro"]:$this->q109_logradouro);
       $this->q109_numero = ($this->q109_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_numero"]:$this->q109_numero);
       $this->q109_complemento = ($this->q109_complemento == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_complemento"]:$this->q109_complemento);
       $this->q109_bairro = ($this->q109_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_bairro"]:$this->q109_bairro);
       $this->q109_cep = ($this->q109_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_cep"]:$this->q109_cep);
       $this->q109_uf = ($this->q109_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_uf"]:$this->q109_uf);
       $this->q109_telefone = ($this->q109_telefone == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_telefone"]:$this->q109_telefone);
       $this->q109_fax = ($this->q109_fax == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_fax"]:$this->q109_fax);
       $this->q109_email = ($this->q109_email == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_email"]:$this->q109_email);
     }else{
       $this->q109_sequencial = ($this->q109_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q109_sequencial"]:$this->q109_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q109_sequencial){ 
      $this->atualizacampos();
     if($this->q109_municipio == null ){ 
       $this->q109_municipio = "0";
     }
     if($this->q109_datacrc == null ){ 
       $this->q109_datacrc = "null";
     }
     if($q109_sequencial == "" || $q109_sequencial == null ){
       $result = db_query("select nextval('meiimportameiregcontador_q109_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: meiimportameiregcontador_q109_sequencial_seq do campo: q109_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q109_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from meiimportameiregcontador_q109_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q109_sequencial)){
         $this->erro_sql = " Campo q109_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q109_sequencial = $q109_sequencial; 
       }
     }
     if(($this->q109_sequencial == null) || ($this->q109_sequencial == "") ){ 
       $this->erro_sql = " Campo q109_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into meiimportameiregcontador(
                                       q109_sequencial 
                                      ,q109_municipio 
                                      ,q109_ufcrc 
                                      ,q109_codigocrc 
                                      ,q109_datacrc 
                                      ,q109_cnpjcpf 
                                      ,q109_nome 
                                      ,q109_tipologradouro 
                                      ,q109_logradouro 
                                      ,q109_numero 
                                      ,q109_complemento 
                                      ,q109_bairro 
                                      ,q109_cep 
                                      ,q109_uf 
                                      ,q109_telefone 
                                      ,q109_fax 
                                      ,q109_email 
                       )
                values (
                                $this->q109_sequencial 
                               ,'$this->q109_municipio' 
                               ,'$this->q109_ufcrc' 
                               ,'$this->q109_codigocrc' 
                               ,".($this->q109_datacrc == "null" || $this->q109_datacrc == ""?"null":"'".$this->q109_datacrc."'")." 
                               ,'$this->q109_cnpjcpf' 
                               ,'$this->q109_nome' 
                               ,'$this->q109_tipologradouro' 
                               ,'$this->q109_logradouro' 
                               ,'$this->q109_numero' 
                               ,'$this->q109_complemento' 
                               ,'$this->q109_bairro' 
                               ,'$this->q109_cep' 
                               ,'$this->q109_uf' 
                               ,'$this->q109_telefone' 
                               ,'$this->q109_fax' 
                               ,'$this->q109_email' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Importação do MEI por Contador ($this->q109_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Importação do MEI por Contador já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Importação do MEI por Contador ($this->q109_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q109_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q109_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16295,'$this->q109_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2855,16295,'','".AddSlashes(pg_result($resaco,0,'q109_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2855,16296,'','".AddSlashes(pg_result($resaco,0,'q109_municipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2855,16298,'','".AddSlashes(pg_result($resaco,0,'q109_ufcrc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2855,16299,'','".AddSlashes(pg_result($resaco,0,'q109_codigocrc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2855,16300,'','".AddSlashes(pg_result($resaco,0,'q109_datacrc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2855,16301,'','".AddSlashes(pg_result($resaco,0,'q109_cnpjcpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2855,16302,'','".AddSlashes(pg_result($resaco,0,'q109_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2855,16303,'','".AddSlashes(pg_result($resaco,0,'q109_tipologradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2855,16304,'','".AddSlashes(pg_result($resaco,0,'q109_logradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2855,16305,'','".AddSlashes(pg_result($resaco,0,'q109_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2855,16306,'','".AddSlashes(pg_result($resaco,0,'q109_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2855,16307,'','".AddSlashes(pg_result($resaco,0,'q109_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2855,16308,'','".AddSlashes(pg_result($resaco,0,'q109_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2855,16309,'','".AddSlashes(pg_result($resaco,0,'q109_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2855,16310,'','".AddSlashes(pg_result($resaco,0,'q109_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2855,16311,'','".AddSlashes(pg_result($resaco,0,'q109_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2855,16312,'','".AddSlashes(pg_result($resaco,0,'q109_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q109_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update meiimportameiregcontador set ";
     $virgula = "";
     if(trim($this->q109_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q109_sequencial"])){ 
       $sql  .= $virgula." q109_sequencial = $this->q109_sequencial ";
       $virgula = ",";
       if(trim($this->q109_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q109_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q109_municipio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q109_municipio"])){ 
       $sql  .= $virgula." q109_municipio = '$this->q109_municipio' ";
       $virgula = ",";
     }
     if(trim($this->q109_ufcrc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q109_ufcrc"])){ 
       $sql  .= $virgula." q109_ufcrc = '$this->q109_ufcrc' ";
       $virgula = ",";
     }
     if(trim($this->q109_codigocrc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q109_codigocrc"])){ 
       $sql  .= $virgula." q109_codigocrc = '$this->q109_codigocrc' ";
       $virgula = ",";
     }
     if(trim($this->q109_datacrc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q109_datacrc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q109_datacrc_dia"] !="") ){ 
       $sql  .= $virgula." q109_datacrc = '$this->q109_datacrc' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q109_datacrc_dia"])){ 
         $sql  .= $virgula." q109_datacrc = null ";
         $virgula = ",";
       }
     }
     if(trim($this->q109_cnpjcpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q109_cnpjcpf"])){ 
       $sql  .= $virgula." q109_cnpjcpf = '$this->q109_cnpjcpf' ";
       $virgula = ",";
     }
     if(trim($this->q109_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q109_nome"])){ 
       $sql  .= $virgula." q109_nome = '$this->q109_nome' ";
       $virgula = ",";
     }
     if(trim($this->q109_tipologradouro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q109_tipologradouro"])){ 
       $sql  .= $virgula." q109_tipologradouro = '$this->q109_tipologradouro' ";
       $virgula = ",";
     }
     if(trim($this->q109_logradouro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q109_logradouro"])){ 
       $sql  .= $virgula." q109_logradouro = '$this->q109_logradouro' ";
       $virgula = ",";
     }
     if(trim($this->q109_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q109_numero"])){ 
       $sql  .= $virgula." q109_numero = '$this->q109_numero' ";
       $virgula = ",";
     }
     if(trim($this->q109_complemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q109_complemento"])){ 
       $sql  .= $virgula." q109_complemento = '$this->q109_complemento' ";
       $virgula = ",";
     }
     if(trim($this->q109_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q109_bairro"])){ 
       $sql  .= $virgula." q109_bairro = '$this->q109_bairro' ";
       $virgula = ",";
     }
     if(trim($this->q109_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q109_cep"])){ 
       $sql  .= $virgula." q109_cep = '$this->q109_cep' ";
       $virgula = ",";
     }
     if(trim($this->q109_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q109_uf"])){ 
       $sql  .= $virgula." q109_uf = '$this->q109_uf' ";
       $virgula = ",";
     }
     if(trim($this->q109_telefone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q109_telefone"])){ 
       $sql  .= $virgula." q109_telefone = '$this->q109_telefone' ";
       $virgula = ",";
     }
     if(trim($this->q109_fax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q109_fax"])){ 
       $sql  .= $virgula." q109_fax = '$this->q109_fax' ";
       $virgula = ",";
     }
     if(trim($this->q109_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q109_email"])){ 
       $sql  .= $virgula." q109_email = '$this->q109_email' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q109_sequencial!=null){
       $sql .= " q109_sequencial = $this->q109_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q109_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16295,'$this->q109_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q109_sequencial"]) || $this->q109_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2855,16295,'".AddSlashes(pg_result($resaco,$conresaco,'q109_sequencial'))."','$this->q109_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q109_municipio"]) || $this->q109_municipio != "")
           $resac = db_query("insert into db_acount values($acount,2855,16296,'".AddSlashes(pg_result($resaco,$conresaco,'q109_municipio'))."','$this->q109_municipio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q109_ufcrc"]) || $this->q109_ufcrc != "")
           $resac = db_query("insert into db_acount values($acount,2855,16298,'".AddSlashes(pg_result($resaco,$conresaco,'q109_ufcrc'))."','$this->q109_ufcrc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q109_codigocrc"]) || $this->q109_codigocrc != "")
           $resac = db_query("insert into db_acount values($acount,2855,16299,'".AddSlashes(pg_result($resaco,$conresaco,'q109_codigocrc'))."','$this->q109_codigocrc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q109_datacrc"]) || $this->q109_datacrc != "")
           $resac = db_query("insert into db_acount values($acount,2855,16300,'".AddSlashes(pg_result($resaco,$conresaco,'q109_datacrc'))."','$this->q109_datacrc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q109_cnpjcpf"]) || $this->q109_cnpjcpf != "")
           $resac = db_query("insert into db_acount values($acount,2855,16301,'".AddSlashes(pg_result($resaco,$conresaco,'q109_cnpjcpf'))."','$this->q109_cnpjcpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q109_nome"]) || $this->q109_nome != "")
           $resac = db_query("insert into db_acount values($acount,2855,16302,'".AddSlashes(pg_result($resaco,$conresaco,'q109_nome'))."','$this->q109_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q109_tipologradouro"]) || $this->q109_tipologradouro != "")
           $resac = db_query("insert into db_acount values($acount,2855,16303,'".AddSlashes(pg_result($resaco,$conresaco,'q109_tipologradouro'))."','$this->q109_tipologradouro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q109_logradouro"]) || $this->q109_logradouro != "")
           $resac = db_query("insert into db_acount values($acount,2855,16304,'".AddSlashes(pg_result($resaco,$conresaco,'q109_logradouro'))."','$this->q109_logradouro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q109_numero"]) || $this->q109_numero != "")
           $resac = db_query("insert into db_acount values($acount,2855,16305,'".AddSlashes(pg_result($resaco,$conresaco,'q109_numero'))."','$this->q109_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q109_complemento"]) || $this->q109_complemento != "")
           $resac = db_query("insert into db_acount values($acount,2855,16306,'".AddSlashes(pg_result($resaco,$conresaco,'q109_complemento'))."','$this->q109_complemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q109_bairro"]) || $this->q109_bairro != "")
           $resac = db_query("insert into db_acount values($acount,2855,16307,'".AddSlashes(pg_result($resaco,$conresaco,'q109_bairro'))."','$this->q109_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q109_cep"]) || $this->q109_cep != "")
           $resac = db_query("insert into db_acount values($acount,2855,16308,'".AddSlashes(pg_result($resaco,$conresaco,'q109_cep'))."','$this->q109_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q109_uf"]) || $this->q109_uf != "")
           $resac = db_query("insert into db_acount values($acount,2855,16309,'".AddSlashes(pg_result($resaco,$conresaco,'q109_uf'))."','$this->q109_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q109_telefone"]) || $this->q109_telefone != "")
           $resac = db_query("insert into db_acount values($acount,2855,16310,'".AddSlashes(pg_result($resaco,$conresaco,'q109_telefone'))."','$this->q109_telefone',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q109_fax"]) || $this->q109_fax != "")
           $resac = db_query("insert into db_acount values($acount,2855,16311,'".AddSlashes(pg_result($resaco,$conresaco,'q109_fax'))."','$this->q109_fax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q109_email"]) || $this->q109_email != "")
           $resac = db_query("insert into db_acount values($acount,2855,16312,'".AddSlashes(pg_result($resaco,$conresaco,'q109_email'))."','$this->q109_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Importação do MEI por Contador nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q109_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Importação do MEI por Contador nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q109_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q109_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q109_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q109_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16295,'$q109_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2855,16295,'','".AddSlashes(pg_result($resaco,$iresaco,'q109_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2855,16296,'','".AddSlashes(pg_result($resaco,$iresaco,'q109_municipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2855,16298,'','".AddSlashes(pg_result($resaco,$iresaco,'q109_ufcrc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2855,16299,'','".AddSlashes(pg_result($resaco,$iresaco,'q109_codigocrc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2855,16300,'','".AddSlashes(pg_result($resaco,$iresaco,'q109_datacrc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2855,16301,'','".AddSlashes(pg_result($resaco,$iresaco,'q109_cnpjcpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2855,16302,'','".AddSlashes(pg_result($resaco,$iresaco,'q109_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2855,16303,'','".AddSlashes(pg_result($resaco,$iresaco,'q109_tipologradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2855,16304,'','".AddSlashes(pg_result($resaco,$iresaco,'q109_logradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2855,16305,'','".AddSlashes(pg_result($resaco,$iresaco,'q109_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2855,16306,'','".AddSlashes(pg_result($resaco,$iresaco,'q109_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2855,16307,'','".AddSlashes(pg_result($resaco,$iresaco,'q109_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2855,16308,'','".AddSlashes(pg_result($resaco,$iresaco,'q109_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2855,16309,'','".AddSlashes(pg_result($resaco,$iresaco,'q109_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2855,16310,'','".AddSlashes(pg_result($resaco,$iresaco,'q109_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2855,16311,'','".AddSlashes(pg_result($resaco,$iresaco,'q109_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2855,16312,'','".AddSlashes(pg_result($resaco,$iresaco,'q109_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from meiimportameiregcontador
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q109_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q109_sequencial = $q109_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Importação do MEI por Contador nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q109_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Importação do MEI por Contador nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q109_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q109_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:meiimportameiregcontador";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q109_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiimportameiregcontador ";
     $sql2 = "";
     if($dbwhere==""){
       if($q109_sequencial!=null ){
         $sql2 .= " where meiimportameiregcontador.q109_sequencial = $q109_sequencial "; 
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
   function sql_query_file ( $q109_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiimportameiregcontador ";
     $sql2 = "";
     if($dbwhere==""){
       if($q109_sequencial!=null ){
         $sql2 .= " where meiimportameiregcontador.q109_sequencial = $q109_sequencial "; 
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