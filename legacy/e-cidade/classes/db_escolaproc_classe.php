<?php
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

//MODULO: escola
//CLASSE DA ENTIDADE escolaproc
class cl_escolaproc { 
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
   var $ed82_i_codigo = 0; 
   var $ed82_c_nome = null; 
   var $ed82_c_abrev = null; 
   var $ed82_c_mantenedora = 0; 
   var $ed82_c_email = null; 
   var $ed82_c_rua = null; 
   var $ed82_i_numero = 0; 
   var $ed82_c_complemento = null; 
   var $ed82_c_bairro = null; 
   var $ed82_i_cep = 0; 
   var $ed82_i_censomunic = 0; 
   var $ed82_i_censouf = 0; 
   var $ed82_i_censodistrito = 0; 
   var $ed82_pais = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed82_i_codigo = int8 = Código 
                 ed82_c_nome = char(50) = Nome 
                 ed82_c_abrev = char(20) = Abreviatura 
                 ed82_c_mantenedora = int4 = Mantenedora 
                 ed82_c_email = char(100) = E-mail 
                 ed82_c_rua = char(50) = Rua 
                 ed82_i_numero = int4 = Número 
                 ed82_c_complemento = char(15) = Complemento 
                 ed82_c_bairro = char(50) = Bairro 
                 ed82_i_cep = int4 = CEP 
                 ed82_i_censomunic = int4 = Cidade 
                 ed82_i_censouf = int4 = Estado 
                 ed82_i_censodistrito = int4 = Distrito 
                 ed82_pais = int4 = País 
                 ";
   //funcao construtor da classe 
   function cl_escolaproc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("escolaproc"); 
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
       $this->ed82_i_codigo = ($this->ed82_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed82_i_codigo"]:$this->ed82_i_codigo);
       $this->ed82_c_nome = ($this->ed82_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["ed82_c_nome"]:$this->ed82_c_nome);
       $this->ed82_c_abrev = ($this->ed82_c_abrev == ""?@$GLOBALS["HTTP_POST_VARS"]["ed82_c_abrev"]:$this->ed82_c_abrev);
       $this->ed82_c_mantenedora = ($this->ed82_c_mantenedora == ""?@$GLOBALS["HTTP_POST_VARS"]["ed82_c_mantenedora"]:$this->ed82_c_mantenedora);
       $this->ed82_c_email = ($this->ed82_c_email == ""?@$GLOBALS["HTTP_POST_VARS"]["ed82_c_email"]:$this->ed82_c_email);
       $this->ed82_c_rua = ($this->ed82_c_rua == ""?@$GLOBALS["HTTP_POST_VARS"]["ed82_c_rua"]:$this->ed82_c_rua);
       $this->ed82_i_numero = ($this->ed82_i_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ed82_i_numero"]:$this->ed82_i_numero);
       $this->ed82_c_complemento = ($this->ed82_c_complemento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed82_c_complemento"]:$this->ed82_c_complemento);
       $this->ed82_c_bairro = ($this->ed82_c_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["ed82_c_bairro"]:$this->ed82_c_bairro);
       $this->ed82_i_cep = ($this->ed82_i_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["ed82_i_cep"]:$this->ed82_i_cep);
       $this->ed82_i_censomunic = ($this->ed82_i_censomunic == ""?@$GLOBALS["HTTP_POST_VARS"]["ed82_i_censomunic"]:$this->ed82_i_censomunic);
       $this->ed82_i_censouf = ($this->ed82_i_censouf == ""?@$GLOBALS["HTTP_POST_VARS"]["ed82_i_censouf"]:$this->ed82_i_censouf);
       $this->ed82_i_censodistrito = ($this->ed82_i_censodistrito == ""?@$GLOBALS["HTTP_POST_VARS"]["ed82_i_censodistrito"]:$this->ed82_i_censodistrito);
       $this->ed82_pais = ($this->ed82_pais == ""?@$GLOBALS["HTTP_POST_VARS"]["ed82_pais"]:$this->ed82_pais);
     }else{
       $this->ed82_i_codigo = ($this->ed82_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed82_i_codigo"]:$this->ed82_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed82_i_codigo){ 
      $this->atualizacampos();
     if($this->ed82_c_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "ed82_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed82_c_mantenedora == null ){ 
       $this->erro_sql = " Campo Mantenedora nao Informado.";
       $this->erro_campo = "ed82_c_mantenedora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed82_i_numero == null ){ 
       $this->ed82_i_numero = "null";
     }
     if($this->ed82_i_cep == null ){ 
       $this->ed82_i_cep = "null";
     }
     if($this->ed82_i_censomunic == null ){ 
       $this->ed82_i_censomunic = "null";
     }
     if($this->ed82_i_censouf == null ){ 
       $this->ed82_i_censouf = "null";
     }
     if($this->ed82_i_censodistrito == null ){ 
       $this->ed82_i_censodistrito = "null";
     }
     if($this->ed82_pais == null ){ 
       $this->erro_sql = " Campo País nao Informado.";
       $this->erro_campo = "ed82_pais";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed82_i_codigo == "" || $ed82_i_codigo == null ){
       $result = db_query("select nextval('escolaproc_ed82_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: escolaproc_ed82_i_codigo_seq do campo: ed82_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed82_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from escolaproc_ed82_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed82_i_codigo)){
         $this->erro_sql = " Campo ed82_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed82_i_codigo = $ed82_i_codigo; 
       }
     }
     if(($this->ed82_i_codigo == null) || ($this->ed82_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed82_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into escolaproc(
                                       ed82_i_codigo 
                                      ,ed82_c_nome 
                                      ,ed82_c_abrev 
                                      ,ed82_c_mantenedora 
                                      ,ed82_c_email 
                                      ,ed82_c_rua 
                                      ,ed82_i_numero 
                                      ,ed82_c_complemento 
                                      ,ed82_c_bairro 
                                      ,ed82_i_cep 
                                      ,ed82_i_censomunic 
                                      ,ed82_i_censouf 
                                      ,ed82_i_censodistrito 
                                      ,ed82_pais 
                       )
                values (
                                $this->ed82_i_codigo 
                               ,'$this->ed82_c_nome' 
                               ,'$this->ed82_c_abrev' 
                               ,$this->ed82_c_mantenedora 
                               ,'$this->ed82_c_email' 
                               ,'$this->ed82_c_rua' 
                               ,$this->ed82_i_numero 
                               ,'$this->ed82_c_complemento' 
                               ,'$this->ed82_c_bairro' 
                               ,$this->ed82_i_cep 
                               ,$this->ed82_i_censomunic 
                               ,$this->ed82_i_censouf 
                               ,$this->ed82_i_censodistrito 
                               ,$this->ed82_pais 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Escolas de Procedência de Alunos ($this->ed82_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Escolas de Procedência de Alunos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Escolas de Procedência de Alunos ($this->ed82_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed82_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed82_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008822,'$this->ed82_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010140,1008822,'','".AddSlashes(pg_result($resaco,0,'ed82_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010140,1008823,'','".AddSlashes(pg_result($resaco,0,'ed82_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010140,1008824,'','".AddSlashes(pg_result($resaco,0,'ed82_c_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010140,1008825,'','".AddSlashes(pg_result($resaco,0,'ed82_c_mantenedora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010140,1008826,'','".AddSlashes(pg_result($resaco,0,'ed82_c_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010140,1008827,'','".AddSlashes(pg_result($resaco,0,'ed82_c_rua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010140,1008828,'','".AddSlashes(pg_result($resaco,0,'ed82_i_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010140,1008833,'','".AddSlashes(pg_result($resaco,0,'ed82_c_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010140,1008830,'','".AddSlashes(pg_result($resaco,0,'ed82_c_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010140,1008829,'','".AddSlashes(pg_result($resaco,0,'ed82_i_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010140,1008831,'','".AddSlashes(pg_result($resaco,0,'ed82_i_censomunic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010140,1008832,'','".AddSlashes(pg_result($resaco,0,'ed82_i_censouf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010140,14594,'','".AddSlashes(pg_result($resaco,0,'ed82_i_censodistrito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010140,19898,'','".AddSlashes(pg_result($resaco,0,'ed82_pais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed82_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update escolaproc set ";
     $virgula = "";
     if(trim($this->ed82_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_codigo"])){ 
       $sql  .= $virgula." ed82_i_codigo = $this->ed82_i_codigo ";
       $virgula = ",";
       if(trim($this->ed82_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed82_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed82_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_nome"])){ 
       $sql  .= $virgula." ed82_c_nome = '$this->ed82_c_nome' ";
       $virgula = ",";
       if(trim($this->ed82_c_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "ed82_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed82_c_abrev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_abrev"])){ 
       $sql  .= $virgula." ed82_c_abrev = '$this->ed82_c_abrev' ";
       $virgula = ",";
     }
     if(trim($this->ed82_c_mantenedora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_mantenedora"])){ 
       $sql  .= $virgula." ed82_c_mantenedora = $this->ed82_c_mantenedora ";
       $virgula = ",";
       if(trim($this->ed82_c_mantenedora) == null ){ 
         $this->erro_sql = " Campo Mantenedora nao Informado.";
         $this->erro_campo = "ed82_c_mantenedora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed82_c_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_email"])){ 
       $sql  .= $virgula." ed82_c_email = '$this->ed82_c_email' ";
       $virgula = ",";
     }
     if(trim($this->ed82_c_rua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_rua"])){ 
       $sql  .= $virgula." ed82_c_rua = '$this->ed82_c_rua' ";
       $virgula = ",";
     }
     if(trim($this->ed82_i_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_numero"])){ 
        if(trim($this->ed82_i_numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_numero"])){ 
           $this->ed82_i_numero = "0" ; 
        } 
       $sql  .= $virgula." ed82_i_numero = $this->ed82_i_numero ";
       $virgula = ",";
     }
     if(trim($this->ed82_c_complemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_complemento"])){ 
       $sql  .= $virgula." ed82_c_complemento = '$this->ed82_c_complemento' ";
       $virgula = ",";
     }
     if(trim($this->ed82_c_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_bairro"])){ 
       $sql  .= $virgula." ed82_c_bairro = '$this->ed82_c_bairro' ";
       $virgula = ",";
     }
     if(trim($this->ed82_i_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_cep"])){ 
        if(trim($this->ed82_i_cep)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_cep"])){ 
           $this->ed82_i_cep = "0" ; 
        } 
       $sql  .= $virgula." ed82_i_cep = $this->ed82_i_cep ";
       $virgula = ",";
     }
     if(trim($this->ed82_i_censomunic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_censomunic"])){ 
        if(trim($this->ed82_i_censomunic)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_censomunic"])){ 
           $this->ed82_i_censomunic = "0" ; 
        } 
       $sql  .= $virgula." ed82_i_censomunic = $this->ed82_i_censomunic ";
       $virgula = ",";
     }
     if(trim($this->ed82_i_censouf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_censouf"])){ 
        if(trim($this->ed82_i_censouf)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_censouf"])){ 
           $this->ed82_i_censouf = "0" ; 
        } 
       $sql  .= $virgula." ed82_i_censouf = $this->ed82_i_censouf ";
       $virgula = ",";
     }
     if(trim($this->ed82_i_censodistrito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_censodistrito"])){ 
        if(trim($this->ed82_i_censodistrito)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_censodistrito"])){ 
           $this->ed82_i_censodistrito = "0" ; 
        } 
       $sql  .= $virgula." ed82_i_censodistrito = $this->ed82_i_censodistrito ";
       $virgula = ",";
     }
     if(trim($this->ed82_pais)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_pais"])){ 
       $sql  .= $virgula." ed82_pais = $this->ed82_pais ";
       $virgula = ",";
       if(trim($this->ed82_pais) == null ){ 
         $this->erro_sql = " Campo País nao Informado.";
         $this->erro_campo = "ed82_pais";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed82_i_codigo!=null){
       $sql .= " ed82_i_codigo = $this->ed82_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed82_i_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008822,'$this->ed82_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_codigo"]) || $this->ed82_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010140,1008822,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_i_codigo'))."','$this->ed82_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_nome"]) || $this->ed82_c_nome != "")
             $resac = db_query("insert into db_acount values($acount,1010140,1008823,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_c_nome'))."','$this->ed82_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_abrev"]) || $this->ed82_c_abrev != "")
             $resac = db_query("insert into db_acount values($acount,1010140,1008824,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_c_abrev'))."','$this->ed82_c_abrev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_mantenedora"]) || $this->ed82_c_mantenedora != "")
             $resac = db_query("insert into db_acount values($acount,1010140,1008825,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_c_mantenedora'))."','$this->ed82_c_mantenedora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_email"]) || $this->ed82_c_email != "")
             $resac = db_query("insert into db_acount values($acount,1010140,1008826,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_c_email'))."','$this->ed82_c_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_rua"]) || $this->ed82_c_rua != "")
             $resac = db_query("insert into db_acount values($acount,1010140,1008827,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_c_rua'))."','$this->ed82_c_rua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_numero"]) || $this->ed82_i_numero != "")
             $resac = db_query("insert into db_acount values($acount,1010140,1008828,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_i_numero'))."','$this->ed82_i_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_complemento"]) || $this->ed82_c_complemento != "")
             $resac = db_query("insert into db_acount values($acount,1010140,1008833,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_c_complemento'))."','$this->ed82_c_complemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_bairro"]) || $this->ed82_c_bairro != "")
             $resac = db_query("insert into db_acount values($acount,1010140,1008830,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_c_bairro'))."','$this->ed82_c_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_cep"]) || $this->ed82_i_cep != "")
             $resac = db_query("insert into db_acount values($acount,1010140,1008829,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_i_cep'))."','$this->ed82_i_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_censomunic"]) || $this->ed82_i_censomunic != "")
             $resac = db_query("insert into db_acount values($acount,1010140,1008831,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_i_censomunic'))."','$this->ed82_i_censomunic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_censouf"]) || $this->ed82_i_censouf != "")
             $resac = db_query("insert into db_acount values($acount,1010140,1008832,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_i_censouf'))."','$this->ed82_i_censouf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_censodistrito"]) || $this->ed82_i_censodistrito != "")
             $resac = db_query("insert into db_acount values($acount,1010140,14594,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_i_censodistrito'))."','$this->ed82_i_censodistrito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_pais"]) || $this->ed82_pais != "")
             $resac = db_query("insert into db_acount values($acount,1010140,19898,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_pais'))."','$this->ed82_pais',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Escolas de Procedência de Alunos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed82_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Escolas de Procedência de Alunos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed82_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed82_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   
   
  function alterar2 ($ed82_i_codigo=null) {
     
    $this->atualizacampos();
    $sql = " update escolaproc set ";
    $virgula = "";
    if(trim($this->ed82_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_codigo"])) {
      
      $sql  .= $virgula." ed82_i_codigo = $this->ed82_i_codigo ";
      $virgula = ",";
      if (trim($this->ed82_i_codigo) == null) {
        
        $this->erro_sql = " Campo Código nao Informado.";
        $this->erro_campo = "ed82_i_codigo";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->ed82_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_nome"])) {
      
      $sql  .= $virgula." ed82_c_nome = '$this->ed82_c_nome' ";
      $virgula = ",";
      if (trim($this->ed82_c_nome) == null ) {
        
        $this->erro_sql = " Campo Nome nao Informado.";
        $this->erro_campo = "ed82_c_nome";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if ( isset($this->ed82_c_abrev) ) {
      
      $sql  .= $virgula." ed82_c_abrev = '$this->ed82_c_abrev' ";
      $virgula = ",";
    } else {
    	
      $sql  .= $virgula." ed82_c_abrev = '' ";
      $virgula = ",";
    }
    
    if (trim($this->ed82_c_mantenedora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_mantenedora"])) {
      
      $sql  .= $virgula." ed82_c_mantenedora = $this->ed82_c_mantenedora ";
      $virgula = ",";
      if (trim($this->ed82_c_mantenedora) == null) {
        
        $this->erro_sql = " Campo Mantenedora nao Informado.";
        $this->erro_campo = "ed82_c_mantenedora";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    
    if ( isset($this->ed82_c_email) ) {
      
      $sql  .= $virgula." ed82_c_email = '$this->ed82_c_email' ";
      $virgula = ",";
    } else {
      
      $sql  .= $virgula." ed82_c_email = '' ";
      $virgula = ",";
    }
    
    if ( isset($this->ed82_c_rua)) {
      
      $sql    .= $virgula." ed82_c_rua = '$this->ed82_c_rua' ";
      $virgula = ",";
    } else {
      
      $sql    .= $virgula." ed82_c_rua = '' ";
      $virgula = ",";
    }
    
    if( isset($this->ed82_i_numero)) {
      
      $this->ed82_i_numero = empty($this->ed82_i_numero) ? 'null' : $this->ed82_i_numero; 
      $sql  .= $virgula." ed82_i_numero = $this->ed82_i_numero ";
      $virgula = ",";
    } else {
    	
      $sql  .= $virgula." ed82_i_numero = null ";
      $virgula = ",";
    }
    
    if( isset($this->ed82_c_complemento)) {
      
      $sql    .= $virgula." ed82_c_complemento = '$this->ed82_c_complemento' ";
      $virgula = ",";
    } else {

      $sql    .= $virgula." ed82_c_complemento = '' ";
      $virgula = ",";
    }
    
    if( isset($this->ed82_c_bairro)) {
      
      $sql  .= $virgula." ed82_c_bairro = '$this->ed82_c_bairro' ";
      $virgula = ",";
    } else {
    	
      $sql  .= $virgula." ed82_c_bairro = '' ";
      $virgula = ",";
    }
    
    if( isset($this->ed82_i_cep)) {
      
      $sql  .= $virgula." ed82_i_cep = $this->ed82_i_cep ";
      $virgula = ",";
    } else {
    	
      $sql  .= $virgula." ed82_i_cep = null ";
      $virgula = ",";
    }
    
    if( isset($this->ed82_i_censomunic)) {
      
      $sql  .= $virgula." ed82_i_censomunic = $this->ed82_i_censomunic ";
      $virgula = ",";
    }
    
    if( isset($this->ed82_i_censouf)) {
      
      $sql  .= $virgula." ed82_i_censouf = $this->ed82_i_censouf ";
      $virgula = ",";
    }
    
    if( isset($this->ed82_i_censodistrito)) {
      
      $sql  .= $virgula." ed82_i_censodistrito = $this->ed82_i_censodistrito ";
      $virgula = ",";
    }
    
    if (trim($this->ed82_pais)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed82_pais"])) {
      
      $sql    .= $virgula." ed82_pais = $this->ed82_pais ";
      $virgula = ",";
      if (trim($this->ed82_pais) == null) {
        
        $this->erro_sql = " Campo País nao Informado.";
        $this->erro_campo = "ed82_pais";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if ($ed82_i_codigo!=null) {
      $sql .= " ed82_i_codigo = $this->ed82_i_codigo";
    }
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {
    
      $resaco = $this->sql_record($this->sql_query_file($this->ed82_i_codigo));
      if($this->numrows>0){
    
        for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
    
          $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac = db_query("insert into db_acountkey values($acount,1008822,'$this->ed82_i_codigo','A')");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_codigo"]) || $this->ed82_i_codigo != "")
            $resac = db_query("insert into db_acount values($acount,1010140,1008822,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_i_codigo'))."','$this->ed82_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_nome"]) || $this->ed82_c_nome != "")
            $resac = db_query("insert into db_acount values($acount,1010140,1008823,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_c_nome'))."','$this->ed82_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_abrev"]) || $this->ed82_c_abrev != "")
            $resac = db_query("insert into db_acount values($acount,1010140,1008824,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_c_abrev'))."','$this->ed82_c_abrev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_mantenedora"]) || $this->ed82_c_mantenedora != "")
            $resac = db_query("insert into db_acount values($acount,1010140,1008825,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_c_mantenedora'))."','$this->ed82_c_mantenedora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_email"]) || $this->ed82_c_email != "")
            $resac = db_query("insert into db_acount values($acount,1010140,1008826,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_c_email'))."','$this->ed82_c_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_rua"]) || $this->ed82_c_rua != "")
            $resac = db_query("insert into db_acount values($acount,1010140,1008827,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_c_rua'))."','$this->ed82_c_rua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_numero"]) || $this->ed82_i_numero != "")
            $resac = db_query("insert into db_acount values($acount,1010140,1008828,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_i_numero'))."','$this->ed82_i_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_complemento"]) || $this->ed82_c_complemento != "")
            $resac = db_query("insert into db_acount values($acount,1010140,1008833,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_c_complemento'))."','$this->ed82_c_complemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_c_bairro"]) || $this->ed82_c_bairro != "")
            $resac = db_query("insert into db_acount values($acount,1010140,1008830,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_c_bairro'))."','$this->ed82_c_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_cep"]) || $this->ed82_i_cep != "")
            $resac = db_query("insert into db_acount values($acount,1010140,1008829,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_i_cep'))."','$this->ed82_i_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_censomunic"]) || $this->ed82_i_censomunic != "")
            $resac = db_query("insert into db_acount values($acount,1010140,1008831,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_i_censomunic'))."','$this->ed82_i_censomunic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_censouf"]) || $this->ed82_i_censouf != "")
            $resac = db_query("insert into db_acount values($acount,1010140,1008832,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_i_censouf'))."','$this->ed82_i_censouf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_i_censodistrito"]) || $this->ed82_i_censodistrito != "")
            $resac = db_query("insert into db_acount values($acount,1010140,14594,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_i_censodistrito'))."','$this->ed82_i_censodistrito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ed82_pais"]) || $this->ed82_pais != "")
            $resac = db_query("insert into db_acount values($acount,1010140,19898,'".AddSlashes(pg_result($resaco,$conresaco,'ed82_pais'))."','$this->ed82_pais',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
      }
    }
    $result = db_query($sql);
    if ($result==false) {
      
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Escolas de Procedência de Alunos nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->ed82_i_codigo;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      
      if (pg_affected_rows($result)==0) {
        
        $this->erro_banco = "";
        $this->erro_sql = "Escolas de Procedência de Alunos nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->ed82_i_codigo;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->ed82_i_codigo;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
   
   // funcao para exclusao 
   function excluir ($ed82_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed82_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008822,'$ed82_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010140,1008822,'','".AddSlashes(pg_result($resaco,$iresaco,'ed82_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010140,1008823,'','".AddSlashes(pg_result($resaco,$iresaco,'ed82_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010140,1008824,'','".AddSlashes(pg_result($resaco,$iresaco,'ed82_c_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010140,1008825,'','".AddSlashes(pg_result($resaco,$iresaco,'ed82_c_mantenedora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010140,1008826,'','".AddSlashes(pg_result($resaco,$iresaco,'ed82_c_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010140,1008827,'','".AddSlashes(pg_result($resaco,$iresaco,'ed82_c_rua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010140,1008828,'','".AddSlashes(pg_result($resaco,$iresaco,'ed82_i_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010140,1008833,'','".AddSlashes(pg_result($resaco,$iresaco,'ed82_c_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010140,1008830,'','".AddSlashes(pg_result($resaco,$iresaco,'ed82_c_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010140,1008829,'','".AddSlashes(pg_result($resaco,$iresaco,'ed82_i_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010140,1008831,'','".AddSlashes(pg_result($resaco,$iresaco,'ed82_i_censomunic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010140,1008832,'','".AddSlashes(pg_result($resaco,$iresaco,'ed82_i_censouf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010140,14594,'','".AddSlashes(pg_result($resaco,$iresaco,'ed82_i_censodistrito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010140,19898,'','".AddSlashes(pg_result($resaco,$iresaco,'ed82_pais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from escolaproc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed82_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed82_i_codigo = $ed82_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Escolas de Procedência de Alunos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed82_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Escolas de Procedência de Alunos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed82_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed82_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:escolaproc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed82_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from escolaproc ";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = escolaproc.ed82_pais";
     $sql .= "      left join censouf  on  censouf.ed260_i_codigo = escolaproc.ed82_i_censouf";
     $sql .= "      left join censomunic  on  censomunic.ed261_i_codigo = escolaproc.ed82_i_censomunic";
     $sql .= "      left join censodistrito  on  censodistrito.ed262_i_codigo = escolaproc.ed82_i_censodistrito";     
     $sql2 = "";
     if($dbwhere==""){
       if($ed82_i_codigo!=null ){
         $sql2 .= " where escolaproc.ed82_i_codigo = $ed82_i_codigo "; 
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

  function sql_query_file ( $ed82_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from escolaproc ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed82_i_codigo!=null ){
         $sql2 .= " where escolaproc.ed82_i_codigo = $ed82_i_codigo ";
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

  function sql_query_escola_aluno_vinculado ( $ed82_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

    $sql  = "select {$campos} ";
    $sql .= " from escolaproc ";
    $sql .= "      left join alunoprimat on ed76_i_escola = ed82_i_codigo";
    $sql2 = "";

    if (empty($dbwhere)) {
      if (!empty($ed82_i_codigo)){
        $sql2 .= " where escolaproc.ed82_i_codigo = $ed82_i_codigo ";
      }
    } else if (!empty($dbwhere)) {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if (!empty($ordem)) {
      $sql .= " order by {$ordem}";
    }

    return $sql;
  }
}