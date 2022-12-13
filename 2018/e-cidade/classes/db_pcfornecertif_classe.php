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

//MODULO: Compras
//CLASSE DA ENTIDADE pcfornecertif
class cl_pcfornecertif { 
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
   var $pc74_codigo = 0; 
   var $pc74_pcforne = 0; 
   var $pc74_pctipocertif = 0; 
   var $pc74_solicitante = null; 
   var $pc74_usuario = 0; 
   var $pc74_data_dia = null; 
   var $pc74_data_mes = null; 
   var $pc74_data_ano = null; 
   var $pc74_data = null; 
   var $pc74_hora = null; 
   var $pc74_coddepto = 0; 
   var $pc74_validade_dia = null; 
   var $pc74_validade_mes = null; 
   var $pc74_validade_ano = null; 
   var $pc74_validade = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc74_codigo = int4 = Cod. Certificado 
                 pc74_pcforne = int4 = Fornecedor 
                 pc74_pctipocertif = int4 = Cod. Tipo Certificado 
                 pc74_solicitante = varchar(40) = Solicitante 
                 pc74_usuario = int4 = Cod. Usuário 
                 pc74_data = date = Data 
                 pc74_hora = char(5) = Hora 
                 pc74_coddepto = int4 = Departamento 
                 pc74_validade = date = Data de Validade 
                 ";
   //funcao construtor da classe 
   function cl_pcfornecertif() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcfornecertif"); 
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
       $this->pc74_codigo = ($this->pc74_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc74_codigo"]:$this->pc74_codigo);
       $this->pc74_pcforne = ($this->pc74_pcforne == ""?@$GLOBALS["HTTP_POST_VARS"]["pc74_pcforne"]:$this->pc74_pcforne);
       $this->pc74_pctipocertif = ($this->pc74_pctipocertif == ""?@$GLOBALS["HTTP_POST_VARS"]["pc74_pctipocertif"]:$this->pc74_pctipocertif);
       $this->pc74_solicitante = ($this->pc74_solicitante == ""?@$GLOBALS["HTTP_POST_VARS"]["pc74_solicitante"]:$this->pc74_solicitante);
       $this->pc74_usuario = ($this->pc74_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["pc74_usuario"]:$this->pc74_usuario);
       if($this->pc74_data == ""){
         $this->pc74_data_dia = ($this->pc74_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc74_data_dia"]:$this->pc74_data_dia);
         $this->pc74_data_mes = ($this->pc74_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc74_data_mes"]:$this->pc74_data_mes);
         $this->pc74_data_ano = ($this->pc74_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc74_data_ano"]:$this->pc74_data_ano);
         if($this->pc74_data_dia != ""){
            $this->pc74_data = $this->pc74_data_ano."-".$this->pc74_data_mes."-".$this->pc74_data_dia;
         }
       }
       $this->pc74_hora = ($this->pc74_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["pc74_hora"]:$this->pc74_hora);
       $this->pc74_coddepto = ($this->pc74_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["pc74_coddepto"]:$this->pc74_coddepto);
       if($this->pc74_validade == ""){
         $this->pc74_validade_dia = ($this->pc74_validade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc74_validade_dia"]:$this->pc74_validade_dia);
         $this->pc74_validade_mes = ($this->pc74_validade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc74_validade_mes"]:$this->pc74_validade_mes);
         $this->pc74_validade_ano = ($this->pc74_validade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc74_validade_ano"]:$this->pc74_validade_ano);
         if($this->pc74_validade_dia != ""){
            $this->pc74_validade = $this->pc74_validade_ano."-".$this->pc74_validade_mes."-".$this->pc74_validade_dia;
         }
       }
     }else{
       $this->pc74_codigo = ($this->pc74_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc74_codigo"]:$this->pc74_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($pc74_codigo){ 
      $this->atualizacampos();
     if($this->pc74_pcforne == null ){ 
       $this->erro_sql = " Campo Fornecedor nao Informado.";
       $this->erro_campo = "pc74_pcforne";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc74_pctipocertif == null ){ 
       $this->erro_sql = " Campo Cod. Tipo Certificado nao Informado.";
       $this->erro_campo = "pc74_pctipocertif";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc74_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "pc74_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc74_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "pc74_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc74_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "pc74_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc74_coddepto == null ){ 
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "pc74_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
/*     if($this->pc74_validade == null ){ 
       $this->erro_sql = " Campo Data de Validade nao Informado.";
       $this->erro_campo = "pc74_validade_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }*/
     if($pc74_codigo == "" || $pc74_codigo == null ){
       $result = db_query("select nextval('pcfornecertif_pc74_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcfornecertif_pc74_codigo_seq do campo: pc74_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc74_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pcfornecertif_pc74_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc74_codigo)){
         $this->erro_sql = " Campo pc74_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc74_codigo = $pc74_codigo; 
       }
     }
     if(($this->pc74_codigo == null) || ($this->pc74_codigo == "") ){ 
       $this->erro_sql = " Campo pc74_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcfornecertif(
                                       pc74_codigo 
                                      ,pc74_pcforne 
                                      ,pc74_pctipocertif 
                                      ,pc74_solicitante 
                                      ,pc74_usuario 
                                      ,pc74_data 
                                      ,pc74_hora 
                                      ,pc74_coddepto 
                                      ,pc74_validade 
                       )
                values (
                                $this->pc74_codigo 
                               ,$this->pc74_pcforne 
                               ,$this->pc74_pctipocertif 
                               ,'$this->pc74_solicitante' 
                               ,$this->pc74_usuario 
                               ,".($this->pc74_data == "null" || $this->pc74_data == ""?"null":"'".$this->pc74_data."'")." 
                               ,'$this->pc74_hora' 
                               ,$this->pc74_coddepto 
                               ,".($this->pc74_validade == "null" || $this->pc74_validade == ""?"null":"'".$this->pc74_validade."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "certificados de um fornecedor ($this->pc74_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "certificados de um fornecedor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "certificados de um fornecedor ($this->pc74_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc74_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc74_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7795,'$this->pc74_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1305,7795,'','".AddSlashes(pg_result($resaco,0,'pc74_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1305,7796,'','".AddSlashes(pg_result($resaco,0,'pc74_pcforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1305,7797,'','".AddSlashes(pg_result($resaco,0,'pc74_pctipocertif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1305,7798,'','".AddSlashes(pg_result($resaco,0,'pc74_solicitante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1305,7801,'','".AddSlashes(pg_result($resaco,0,'pc74_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1305,7799,'','".AddSlashes(pg_result($resaco,0,'pc74_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1305,7800,'','".AddSlashes(pg_result($resaco,0,'pc74_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1305,16549,'','".AddSlashes(pg_result($resaco,0,'pc74_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1305,16550,'','".AddSlashes(pg_result($resaco,0,'pc74_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc74_codigo=null) { 
      $this->atualizacampos();
     $sql = " update pcfornecertif set ";
     $virgula = "";
     if(trim($this->pc74_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc74_codigo"])){ 
       $sql  .= $virgula." pc74_codigo = $this->pc74_codigo ";
       $virgula = ",";
       if(trim($this->pc74_codigo) == null ){ 
         $this->erro_sql = " Campo Cod. Certificado nao Informado.";
         $this->erro_campo = "pc74_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc74_pcforne)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc74_pcforne"])){ 
       $sql  .= $virgula." pc74_pcforne = $this->pc74_pcforne ";
       $virgula = ",";
       if(trim($this->pc74_pcforne) == null ){ 
         $this->erro_sql = " Campo Fornecedor nao Informado.";
         $this->erro_campo = "pc74_pcforne";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc74_pctipocertif)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc74_pctipocertif"])){ 
       $sql  .= $virgula." pc74_pctipocertif = $this->pc74_pctipocertif ";
       $virgula = ",";
       if(trim($this->pc74_pctipocertif) == null ){ 
         $this->erro_sql = " Campo Cod. Tipo Certificado nao Informado.";
         $this->erro_campo = "pc74_pctipocertif";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc74_solicitante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc74_solicitante"])){ 
       $sql  .= $virgula." pc74_solicitante = '$this->pc74_solicitante' ";
       $virgula = ",";
     }
     if(trim($this->pc74_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc74_usuario"])){ 
       $sql  .= $virgula." pc74_usuario = $this->pc74_usuario ";
       $virgula = ",";
       if(trim($this->pc74_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "pc74_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc74_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc74_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc74_data_dia"] !="") ){ 
       $sql  .= $virgula." pc74_data = '$this->pc74_data' ";
       $virgula = ",";
       if(trim($this->pc74_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "pc74_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc74_data_dia"])){ 
         $sql  .= $virgula." pc74_data = null ";
         $virgula = ",";
         if(trim($this->pc74_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "pc74_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc74_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc74_hora"])){ 
       $sql  .= $virgula." pc74_hora = '$this->pc74_hora' ";
       $virgula = ",";
       if(trim($this->pc74_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "pc74_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc74_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc74_coddepto"])){ 
       $sql  .= $virgula." pc74_coddepto = $this->pc74_coddepto ";
       $virgula = ",";
       if(trim($this->pc74_coddepto) == null ){ 
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "pc74_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc74_validade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc74_validade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc74_validade_dia"] !="") ){ 
       $sql  .= $virgula." pc74_validade = '$this->pc74_validade' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc74_validade_dia"]) || $this->pc74_validade == null){ 
         $sql  .= $virgula." pc74_validade = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($pc74_codigo!=null){
       $sql .= " pc74_codigo = $this->pc74_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc74_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7795,'$this->pc74_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc74_codigo"]) || $this->pc74_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1305,7795,'".AddSlashes(pg_result($resaco,$conresaco,'pc74_codigo'))."','$this->pc74_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc74_pcforne"]) || $this->pc74_pcforne != "")
           $resac = db_query("insert into db_acount values($acount,1305,7796,'".AddSlashes(pg_result($resaco,$conresaco,'pc74_pcforne'))."','$this->pc74_pcforne',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc74_pctipocertif"]) || $this->pc74_pctipocertif != "")
           $resac = db_query("insert into db_acount values($acount,1305,7797,'".AddSlashes(pg_result($resaco,$conresaco,'pc74_pctipocertif'))."','$this->pc74_pctipocertif',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc74_solicitante"]) || $this->pc74_solicitante != "")
           $resac = db_query("insert into db_acount values($acount,1305,7798,'".AddSlashes(pg_result($resaco,$conresaco,'pc74_solicitante'))."','$this->pc74_solicitante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc74_usuario"]) || $this->pc74_usuario != "")
           $resac = db_query("insert into db_acount values($acount,1305,7801,'".AddSlashes(pg_result($resaco,$conresaco,'pc74_usuario'))."','$this->pc74_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc74_data"]) || $this->pc74_data != "")
           $resac = db_query("insert into db_acount values($acount,1305,7799,'".AddSlashes(pg_result($resaco,$conresaco,'pc74_data'))."','$this->pc74_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc74_hora"]) || $this->pc74_hora != "")
           $resac = db_query("insert into db_acount values($acount,1305,7800,'".AddSlashes(pg_result($resaco,$conresaco,'pc74_hora'))."','$this->pc74_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc74_coddepto"]) || $this->pc74_coddepto != "")
           $resac = db_query("insert into db_acount values($acount,1305,16549,'".AddSlashes(pg_result($resaco,$conresaco,'pc74_coddepto'))."','$this->pc74_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc74_validade"]) || $this->pc74_validade != "")
           $resac = db_query("insert into db_acount values($acount,1305,16550,'".AddSlashes(pg_result($resaco,$conresaco,'pc74_validade'))."','$this->pc74_validade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "certificados de um fornecedor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc74_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "certificados de um fornecedor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc74_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc74_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc74_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc74_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7795,'$pc74_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1305,7795,'','".AddSlashes(pg_result($resaco,$iresaco,'pc74_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1305,7796,'','".AddSlashes(pg_result($resaco,$iresaco,'pc74_pcforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1305,7797,'','".AddSlashes(pg_result($resaco,$iresaco,'pc74_pctipocertif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1305,7798,'','".AddSlashes(pg_result($resaco,$iresaco,'pc74_solicitante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1305,7801,'','".AddSlashes(pg_result($resaco,$iresaco,'pc74_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1305,7799,'','".AddSlashes(pg_result($resaco,$iresaco,'pc74_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1305,7800,'','".AddSlashes(pg_result($resaco,$iresaco,'pc74_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1305,16549,'','".AddSlashes(pg_result($resaco,$iresaco,'pc74_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1305,16550,'','".AddSlashes(pg_result($resaco,$iresaco,'pc74_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcfornecertif
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc74_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc74_codigo = $pc74_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "certificados de um fornecedor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc74_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "certificados de um fornecedor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc74_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc74_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcfornecertif";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc74_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcfornecertif ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcfornecertif.pc74_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = pcfornecertif.pc74_coddepto";
     $sql .= "      inner join pcforne  on  pcforne.pc60_numcgm = pcfornecertif.pc74_pcforne";
     $sql .= "      inner join pctipocertif  on  pctipocertif.pc70_codigo = pcfornecertif.pc74_pctipocertif";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcforne.pc60_numcgm";
     $sql .= "      inner join db_usuarios AS usua  on  usua.id_usuario = pcforne.pc60_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($pc74_codigo!=null ){
         $sql2 .= " where pcfornecertif.pc74_codigo = $pc74_codigo "; 
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
   function sql_query_file ( $pc74_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcfornecertif ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc74_codigo!=null ){
         $sql2 .= " where pcfornecertif.pc74_codigo = $pc74_codigo "; 
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