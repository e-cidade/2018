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

//MODULO: itbi
//CLASSE DA ENTIDADE itbinome
class cl_itbinome { 
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
   var $it03_seq = 0; 
   var $it03_guia = 0; 
   var $it03_tipo = null; 
   var $it03_princ = 'f'; 
   var $it03_nome = null; 
   var $it03_sexo = null; 
   var $it03_cpfcnpj = null; 
   var $it03_endereco = null; 
   var $it03_numero = 0; 
   var $it03_compl = null; 
   var $it03_cxpostal = null; 
   var $it03_bairro = null; 
   var $it03_munic = null; 
   var $it03_uf = null; 
   var $it03_cep = null; 
   var $it03_mail = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 it03_seq = int8 = Sequencia 
                 it03_guia = int8 = Número da guia de ITBI 
                 it03_tipo = char(1) = Tipo 
                 it03_princ = bool = Principal 
                 it03_nome = varchar(40) = Nome 
                 it03_sexo = char(1) = Sexo 
                 it03_cpfcnpj = varchar(14) = CNPJ/CPF 
                 it03_endereco = varchar(100) = Endereco 
                 it03_numero = int8 = Número 
                 it03_compl = varchar(100) = Complemento 
                 it03_cxpostal = varchar(20) = Caixa postal 
                 it03_bairro = varchar(40) = Bairro 
                 it03_munic = varchar(40) = Município 
                 it03_uf = varchar(2) = UF 
                 it03_cep = varchar(8) = CEP 
                 it03_mail = varchar(50) = e-mail 
                 ";
   //funcao construtor da classe 
   function cl_itbinome() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itbinome"); 
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
       $this->it03_seq = ($this->it03_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["it03_seq"]:$this->it03_seq);
       $this->it03_guia = ($this->it03_guia == ""?@$GLOBALS["HTTP_POST_VARS"]["it03_guia"]:$this->it03_guia);
       $this->it03_tipo = ($this->it03_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["it03_tipo"]:$this->it03_tipo);
       $this->it03_princ = ($this->it03_princ == "f"?@$GLOBALS["HTTP_POST_VARS"]["it03_princ"]:$this->it03_princ);
       $this->it03_nome = ($this->it03_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["it03_nome"]:$this->it03_nome);
       $this->it03_sexo = ($this->it03_sexo == ""?@$GLOBALS["HTTP_POST_VARS"]["it03_sexo"]:$this->it03_sexo);
       $this->it03_cpfcnpj = ($this->it03_cpfcnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["it03_cpfcnpj"]:$this->it03_cpfcnpj);
       $this->it03_endereco = ($this->it03_endereco == ""?@$GLOBALS["HTTP_POST_VARS"]["it03_endereco"]:$this->it03_endereco);
       $this->it03_numero = ($this->it03_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["it03_numero"]:$this->it03_numero);
       $this->it03_compl = ($this->it03_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["it03_compl"]:$this->it03_compl);
       $this->it03_cxpostal = ($this->it03_cxpostal == ""?@$GLOBALS["HTTP_POST_VARS"]["it03_cxpostal"]:$this->it03_cxpostal);
       $this->it03_bairro = ($this->it03_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["it03_bairro"]:$this->it03_bairro);
       $this->it03_munic = ($this->it03_munic == ""?@$GLOBALS["HTTP_POST_VARS"]["it03_munic"]:$this->it03_munic);
       $this->it03_uf = ($this->it03_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["it03_uf"]:$this->it03_uf);
       $this->it03_cep = ($this->it03_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["it03_cep"]:$this->it03_cep);
       $this->it03_mail = ($this->it03_mail == ""?@$GLOBALS["HTTP_POST_VARS"]["it03_mail"]:$this->it03_mail);
     }else{
       $this->it03_seq = ($this->it03_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["it03_seq"]:$this->it03_seq);
     }
   }
   // funcao para inclusao
   function incluir ($it03_seq){ 
      $this->atualizacampos();
     if($this->it03_guia == null ){ 
       $this->erro_sql = " Campo Número da guia de ITBI não informado.";
       $this->erro_campo = "it03_guia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it03_tipo == null ){ 
       $this->erro_sql = " Campo Tipo não informado.";
       $this->erro_campo = "it03_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it03_princ == null ){ 
       $this->erro_sql = " Campo Principal não informado.";
       $this->erro_campo = "it03_princ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it03_nome == null ){ 
       $this->erro_sql = " Campo Nome não informado.";
       $this->erro_campo = "it03_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it03_sexo == null ){ 
       $this->erro_sql = " Campo Sexo não informado.";
       $this->erro_campo = "it03_sexo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it03_numero == null ){ 
       $this->it03_numero = "0";
     }
     if($this->it03_cxpostal == null ){ 
       $this->it03_cxpostal = "0";
     }
     if($it03_seq == "" || $it03_seq == null ){
       $result = db_query("select nextval('itbinome_it03_seq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: itbinome_it03_seq_seq do campo: it03_seq"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->it03_seq = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from itbinome_it03_seq_seq");
       if(($result != false) && (pg_result($result,0,0) < $it03_seq)){
         $this->erro_sql = " Campo it03_seq maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->it03_seq = $it03_seq; 
       }
     }
     if(($this->it03_seq == null) || ($this->it03_seq == "") ){ 
       $this->erro_sql = " Campo it03_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itbinome(
                                       it03_seq 
                                      ,it03_guia 
                                      ,it03_tipo 
                                      ,it03_princ 
                                      ,it03_nome 
                                      ,it03_sexo 
                                      ,it03_cpfcnpj 
                                      ,it03_endereco 
                                      ,it03_numero 
                                      ,it03_compl 
                                      ,it03_cxpostal 
                                      ,it03_bairro 
                                      ,it03_munic 
                                      ,it03_uf 
                                      ,it03_cep 
                                      ,it03_mail 
                       )
                values (
                                $this->it03_seq 
                               ,$this->it03_guia 
                               ,'$this->it03_tipo' 
                               ,'$this->it03_princ' 
                               ,'$this->it03_nome' 
                               ,'$this->it03_sexo' 
                               ,'$this->it03_cpfcnpj' 
                               ,'$this->it03_endereco' 
                               ,$this->it03_numero 
                               ,'$this->it03_compl' 
                               ,'$this->it03_cxpostal' 
                               ,'$this->it03_bairro' 
                               ,'$this->it03_munic' 
                               ,'$this->it03_uf' 
                               ,'$this->it03_cep' 
                               ,'$this->it03_mail' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Nome(s) da guia ($this->it03_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Nome(s) da guia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Nome(s) da guia ($this->it03_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it03_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->it03_seq  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5378,'$this->it03_seq','I')");
         $resac = db_query("insert into db_acount values($acount,794,5378,'','".AddSlashes(pg_result($resaco,0,'it03_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,794,5400,'','".AddSlashes(pg_result($resaco,0,'it03_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,794,8996,'','".AddSlashes(pg_result($resaco,0,'it03_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,794,8997,'','".AddSlashes(pg_result($resaco,0,'it03_princ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,794,5379,'','".AddSlashes(pg_result($resaco,0,'it03_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,794,9009,'','".AddSlashes(pg_result($resaco,0,'it03_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,794,5380,'','".AddSlashes(pg_result($resaco,0,'it03_cpfcnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,794,5381,'','".AddSlashes(pg_result($resaco,0,'it03_endereco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,794,5382,'','".AddSlashes(pg_result($resaco,0,'it03_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,794,5383,'','".AddSlashes(pg_result($resaco,0,'it03_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,794,5384,'','".AddSlashes(pg_result($resaco,0,'it03_cxpostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,794,5385,'','".AddSlashes(pg_result($resaco,0,'it03_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,794,5386,'','".AddSlashes(pg_result($resaco,0,'it03_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,794,5388,'','".AddSlashes(pg_result($resaco,0,'it03_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,794,5387,'','".AddSlashes(pg_result($resaco,0,'it03_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,794,5391,'','".AddSlashes(pg_result($resaco,0,'it03_mail'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($it03_seq=null) { 
      $this->atualizacampos();
     $sql = " update itbinome set ";
     $virgula = "";
     if(trim($this->it03_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it03_seq"])){ 
       $sql  .= $virgula." it03_seq = $this->it03_seq ";
       $virgula = ",";
       if(trim($this->it03_seq) == null ){ 
         $this->erro_sql = " Campo Sequencia não informado.";
         $this->erro_campo = "it03_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it03_guia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it03_guia"])){ 
       $sql  .= $virgula." it03_guia = $this->it03_guia ";
       $virgula = ",";
       if(trim($this->it03_guia) == null ){ 
         $this->erro_sql = " Campo Número da guia de ITBI não informado.";
         $this->erro_campo = "it03_guia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it03_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it03_tipo"])){ 
       $sql  .= $virgula." it03_tipo = '$this->it03_tipo' ";
       $virgula = ",";
       if(trim($this->it03_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo não informado.";
         $this->erro_campo = "it03_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it03_princ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it03_princ"])){ 
       $sql  .= $virgula." it03_princ = '$this->it03_princ' ";
       $virgula = ",";
       if(trim($this->it03_princ) == null ){ 
         $this->erro_sql = " Campo Principal não informado.";
         $this->erro_campo = "it03_princ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it03_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it03_nome"])){ 
       $sql  .= $virgula." it03_nome = '$this->it03_nome' ";
       $virgula = ",";
       if(trim($this->it03_nome) == null ){ 
         $this->erro_sql = " Campo Nome não informado.";
         $this->erro_campo = "it03_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it03_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it03_sexo"])){ 
       $sql  .= $virgula." it03_sexo = '$this->it03_sexo' ";
       $virgula = ",";
       if(trim($this->it03_sexo) == null ){ 
         $this->erro_sql = " Campo Sexo não informado.";
         $this->erro_campo = "it03_sexo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it03_cpfcnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it03_cpfcnpj"])){ 
       $sql  .= $virgula." it03_cpfcnpj = '$this->it03_cpfcnpj' ";
       $virgula = ",";
     }
     if(trim($this->it03_endereco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it03_endereco"])){ 
       $sql  .= $virgula." it03_endereco = '$this->it03_endereco' ";
       $virgula = ",";
     }
     if(trim($this->it03_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it03_numero"])){ 
        if(trim($this->it03_numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["it03_numero"])){ 
           $this->it03_numero = "0" ; 
        } 
       $sql  .= $virgula." it03_numero = $this->it03_numero ";
       $virgula = ",";
     }
     if(trim($this->it03_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it03_compl"])){ 
       $sql  .= $virgula." it03_compl = '$this->it03_compl' ";
       $virgula = ",";
     }
     if(trim($this->it03_cxpostal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it03_cxpostal"])){ 
       $sql  .= $virgula." it03_cxpostal = '$this->it03_cxpostal' ";
       $virgula = ",";
     }
     if(trim($this->it03_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it03_bairro"])){ 
       $sql  .= $virgula." it03_bairro = '$this->it03_bairro' ";
       $virgula = ",";
     }
     if(trim($this->it03_munic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it03_munic"])){ 
       $sql  .= $virgula." it03_munic = '$this->it03_munic' ";
       $virgula = ",";
     }
     if(trim($this->it03_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it03_uf"])){ 
       $sql  .= $virgula." it03_uf = '$this->it03_uf' ";
       $virgula = ",";
     }
     if(trim($this->it03_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it03_cep"])){ 
       $sql  .= $virgula." it03_cep = '$this->it03_cep' ";
       $virgula = ",";
     }
     if(trim($this->it03_mail)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it03_mail"])){ 
       $sql  .= $virgula." it03_mail = '$this->it03_mail' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($it03_seq!=null){
       $sql .= " it03_seq = $this->it03_seq";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->it03_seq));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5378,'$this->it03_seq','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it03_seq"]) || $this->it03_seq != "")
             $resac = db_query("insert into db_acount values($acount,794,5378,'".AddSlashes(pg_result($resaco,$conresaco,'it03_seq'))."','$this->it03_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it03_guia"]) || $this->it03_guia != "")
             $resac = db_query("insert into db_acount values($acount,794,5400,'".AddSlashes(pg_result($resaco,$conresaco,'it03_guia'))."','$this->it03_guia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it03_tipo"]) || $this->it03_tipo != "")
             $resac = db_query("insert into db_acount values($acount,794,8996,'".AddSlashes(pg_result($resaco,$conresaco,'it03_tipo'))."','$this->it03_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it03_princ"]) || $this->it03_princ != "")
             $resac = db_query("insert into db_acount values($acount,794,8997,'".AddSlashes(pg_result($resaco,$conresaco,'it03_princ'))."','$this->it03_princ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it03_nome"]) || $this->it03_nome != "")
             $resac = db_query("insert into db_acount values($acount,794,5379,'".AddSlashes(pg_result($resaco,$conresaco,'it03_nome'))."','$this->it03_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it03_sexo"]) || $this->it03_sexo != "")
             $resac = db_query("insert into db_acount values($acount,794,9009,'".AddSlashes(pg_result($resaco,$conresaco,'it03_sexo'))."','$this->it03_sexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it03_cpfcnpj"]) || $this->it03_cpfcnpj != "")
             $resac = db_query("insert into db_acount values($acount,794,5380,'".AddSlashes(pg_result($resaco,$conresaco,'it03_cpfcnpj'))."','$this->it03_cpfcnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it03_endereco"]) || $this->it03_endereco != "")
             $resac = db_query("insert into db_acount values($acount,794,5381,'".AddSlashes(pg_result($resaco,$conresaco,'it03_endereco'))."','$this->it03_endereco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it03_numero"]) || $this->it03_numero != "")
             $resac = db_query("insert into db_acount values($acount,794,5382,'".AddSlashes(pg_result($resaco,$conresaco,'it03_numero'))."','$this->it03_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it03_compl"]) || $this->it03_compl != "")
             $resac = db_query("insert into db_acount values($acount,794,5383,'".AddSlashes(pg_result($resaco,$conresaco,'it03_compl'))."','$this->it03_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it03_cxpostal"]) || $this->it03_cxpostal != "")
             $resac = db_query("insert into db_acount values($acount,794,5384,'".AddSlashes(pg_result($resaco,$conresaco,'it03_cxpostal'))."','$this->it03_cxpostal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it03_bairro"]) || $this->it03_bairro != "")
             $resac = db_query("insert into db_acount values($acount,794,5385,'".AddSlashes(pg_result($resaco,$conresaco,'it03_bairro'))."','$this->it03_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it03_munic"]) || $this->it03_munic != "")
             $resac = db_query("insert into db_acount values($acount,794,5386,'".AddSlashes(pg_result($resaco,$conresaco,'it03_munic'))."','$this->it03_munic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it03_uf"]) || $this->it03_uf != "")
             $resac = db_query("insert into db_acount values($acount,794,5388,'".AddSlashes(pg_result($resaco,$conresaco,'it03_uf'))."','$this->it03_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it03_cep"]) || $this->it03_cep != "")
             $resac = db_query("insert into db_acount values($acount,794,5387,'".AddSlashes(pg_result($resaco,$conresaco,'it03_cep'))."','$this->it03_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it03_mail"]) || $this->it03_mail != "")
             $resac = db_query("insert into db_acount values($acount,794,5391,'".AddSlashes(pg_result($resaco,$conresaco,'it03_mail'))."','$this->it03_mail',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Nome(s) da guia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->it03_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Nome(s) da guia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->it03_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it03_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($it03_seq=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($it03_seq));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,5378,'$it03_seq','E')");
           $resac  = db_query("insert into db_acount values($acount,794,5378,'','".AddSlashes(pg_result($resaco,$iresaco,'it03_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,794,5400,'','".AddSlashes(pg_result($resaco,$iresaco,'it03_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,794,8996,'','".AddSlashes(pg_result($resaco,$iresaco,'it03_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,794,8997,'','".AddSlashes(pg_result($resaco,$iresaco,'it03_princ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,794,5379,'','".AddSlashes(pg_result($resaco,$iresaco,'it03_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,794,9009,'','".AddSlashes(pg_result($resaco,$iresaco,'it03_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,794,5380,'','".AddSlashes(pg_result($resaco,$iresaco,'it03_cpfcnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,794,5381,'','".AddSlashes(pg_result($resaco,$iresaco,'it03_endereco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,794,5382,'','".AddSlashes(pg_result($resaco,$iresaco,'it03_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,794,5383,'','".AddSlashes(pg_result($resaco,$iresaco,'it03_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,794,5384,'','".AddSlashes(pg_result($resaco,$iresaco,'it03_cxpostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,794,5385,'','".AddSlashes(pg_result($resaco,$iresaco,'it03_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,794,5386,'','".AddSlashes(pg_result($resaco,$iresaco,'it03_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,794,5388,'','".AddSlashes(pg_result($resaco,$iresaco,'it03_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,794,5387,'','".AddSlashes(pg_result($resaco,$iresaco,'it03_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,794,5391,'','".AddSlashes(pg_result($resaco,$iresaco,'it03_mail'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from itbinome
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($it03_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " it03_seq = $it03_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Nome(s) da guia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$it03_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Nome(s) da guia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$it03_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$it03_seq;
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
        $this->erro_sql   = "Record Vazio na Tabela:itbinome";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $it03_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbinome ";
     $sql .= "      inner join itbi  on  itbi.it01_guia = itbinome.it03_guia";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = itbi.it01_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = itbi.it01_coddepto";
     $sql .= "      inner join itbitransacao  on  itbitransacao.it04_codigo = itbi.it01_tipotransacao";
     $sql2 = "";
     if($dbwhere==""){
       if($it03_seq!=null ){
         $sql2 .= " where itbinome.it03_seq = $it03_seq "; 
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
   function sql_query_file ( $it03_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbinome ";
     $sql2 = "";
     if($dbwhere==""){
       if($it03_seq!=null ){
         $sql2 .= " where itbinome.it03_seq = $it03_seq "; 
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
   function sql_queryguia($it03_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbinome ";
     $sql .= "      inner join itbi            on  itbi.it01_guia            = itbinome.it03_guia";
     $sql .= "      left  join itbinomecgm     on  itbinomecgm.it21_itbinome = itbinome.it03_seq";
     $sql .= "      left  join cgm             on  cgm.z01_numcgm            = itbinomecgm.it21_numcgm ";
     $sql .= "      left  join itburbano       on  itburbano.it05_guia       = itbinome.it03_guia";
     $sql .= "      left  join itbirural       on  itbirural.it18_guia       = itbinome.it03_guia";
     $sql .= "      left  join itbidadosimovel on  itbidadosimovel.it22_itbi = itbinome.it03_guia";
     $sql .= "      left  join itbimatric      on  itbimatric.it06_guia      = itbinome.it03_guia";
		 $sql .= "      left  join iptubase				 on  iptubase.j01_matric			 = itbimatric.it06_matric";
		 $sql .= "      left  join lote						 on  lote.j34_idbql						 = iptubase.j01_idbql";
		 $sql .= "      left  join bairro					 on  lote.j34_bairro					 = bairro.j13_codi";
     $sql .= "      inner join itbitransacao   on  itbitransacao.it04_codigo = itbi.it01_tipotransacao";
     $sql .= "      left  join itbiavalia      on  itbiavalia.it14_guia      = itbi.it01_guia";
//   $sql .= "      left  join itbisituacao    on  itbisituacao.it07_guia    = itbi.it01_guia";
     $sql2 = "";
     if($dbwhere==""){
       if($it03_seq!=null ){
         $sql2 .= " where itbinome.it03_seq = $it03_seq "; 
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
