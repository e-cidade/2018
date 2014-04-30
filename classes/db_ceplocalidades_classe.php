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

//MODULO: protocolo
//CLASSE DA ENTIDADE ceplocalidades
class cl_ceplocalidades { 
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
   var $cp05_codlocalidades = 0; 
   var $cp05_sigla = null; 
   var $cp05_localidades = null; 
   var $cp05_cepinicial = null; 
   var $cp05_cepfinal = null; 
   var $cp05_tipo = null; 
   var $cp05_situacao = null; 
   var $cp05_codsubordinacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cp05_codlocalidades = int8 = Codigo da Localidade 
                 cp05_sigla = varchar(2) = Sigla Estado 
                 cp05_localidades = varchar(72) = Cadastro de Localidades 
                 cp05_cepinicial = varchar(8) = Cep inicial 
                 cp05_cepfinal = varchar(8) = Cep final 
                 cp05_tipo = varchar(1) = Tipo 
                 cp05_situacao = varchar(1) = Situação 
                 cp05_codsubordinacao = int8 = Codigo Subordinação 
                 ";
   //funcao construtor da classe 
   function cl_ceplocalidades() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ceplocalidades"); 
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
       $this->cp05_codlocalidades = ($this->cp05_codlocalidades == ""?@$GLOBALS["HTTP_POST_VARS"]["cp05_codlocalidades"]:$this->cp05_codlocalidades);
       $this->cp05_sigla = ($this->cp05_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["cp05_sigla"]:$this->cp05_sigla);
       $this->cp05_localidades = ($this->cp05_localidades == ""?@$GLOBALS["HTTP_POST_VARS"]["cp05_localidades"]:$this->cp05_localidades);
       $this->cp05_cepinicial = ($this->cp05_cepinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["cp05_cepinicial"]:$this->cp05_cepinicial);
       $this->cp05_cepfinal = ($this->cp05_cepfinal == ""?@$GLOBALS["HTTP_POST_VARS"]["cp05_cepfinal"]:$this->cp05_cepfinal);
       $this->cp05_tipo = ($this->cp05_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["cp05_tipo"]:$this->cp05_tipo);
       $this->cp05_situacao = ($this->cp05_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["cp05_situacao"]:$this->cp05_situacao);
       $this->cp05_codsubordinacao = ($this->cp05_codsubordinacao == ""?@$GLOBALS["HTTP_POST_VARS"]["cp05_codsubordinacao"]:$this->cp05_codsubordinacao);
     }else{
       $this->cp05_codlocalidades = ($this->cp05_codlocalidades == ""?@$GLOBALS["HTTP_POST_VARS"]["cp05_codlocalidades"]:$this->cp05_codlocalidades);
     }
   }
   // funcao para inclusao
   function incluir ($cp05_codlocalidades){ 
      $this->atualizacampos();
     if($this->cp05_sigla == null ){ 
       $this->erro_sql = " Campo Sigla Estado nao Informado.";
       $this->erro_campo = "cp05_sigla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp05_localidades == null ){ 
       $this->erro_sql = " Campo Cadastro de Localidades nao Informado.";
       $this->erro_campo = "cp05_localidades";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp05_cepinicial == null ){ 
       $this->erro_sql = " Campo Cep inicial nao Informado.";
       $this->erro_campo = "cp05_cepinicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp05_cepfinal == null ){ 
       $this->erro_sql = " Campo Cep final nao Informado.";
       $this->erro_campo = "cp05_cepfinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp05_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "cp05_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp05_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "cp05_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp05_codsubordinacao == null ){ 
       $this->erro_sql = " Campo Codigo Subordinação nao Informado.";
       $this->erro_campo = "cp05_codsubordinacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cp05_codlocalidades == "" || $cp05_codlocalidades == null ){
       $result = db_query("select nextval('ceplocalidades_cp05_codlocalidades_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ceplocalidades_cp05_codlocalidades_seq do campo: cp05_codlocalidades"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cp05_codlocalidades = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ceplocalidades_cp05_codlocalidades_seq");
       if(($result != false) && (pg_result($result,0,0) < $cp05_codlocalidades)){
         $this->erro_sql = " Campo cp05_codlocalidades maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cp05_codlocalidades = $cp05_codlocalidades; 
       }
     }
     if(($this->cp05_codlocalidades == null) || ($this->cp05_codlocalidades == "") ){ 
       $this->erro_sql = " Campo cp05_codlocalidades nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ceplocalidades(
                                       cp05_codlocalidades 
                                      ,cp05_sigla 
                                      ,cp05_localidades 
                                      ,cp05_cepinicial 
                                      ,cp05_cepfinal 
                                      ,cp05_tipo 
                                      ,cp05_situacao 
                                      ,cp05_codsubordinacao 
                       )
                values (
                                $this->cp05_codlocalidades 
                               ,'$this->cp05_sigla' 
                               ,'$this->cp05_localidades' 
                               ,'$this->cp05_cepinicial' 
                               ,'$this->cp05_cepfinal' 
                               ,'$this->cp05_tipo' 
                               ,'$this->cp05_situacao' 
                               ,$this->cp05_codsubordinacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Localidades ($this->cp05_codlocalidades) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Localidades já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Localidades ($this->cp05_codlocalidades) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cp05_codlocalidades;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cp05_codlocalidades));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7189,'$this->cp05_codlocalidades','I')");
       $resac = db_query("insert into db_acount values($acount,1196,7189,'','".AddSlashes(pg_result($resaco,0,'cp05_codlocalidades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1196,7190,'','".AddSlashes(pg_result($resaco,0,'cp05_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1196,7191,'','".AddSlashes(pg_result($resaco,0,'cp05_localidades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1196,7192,'','".AddSlashes(pg_result($resaco,0,'cp05_cepinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1196,7193,'','".AddSlashes(pg_result($resaco,0,'cp05_cepfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1196,7194,'','".AddSlashes(pg_result($resaco,0,'cp05_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1196,7195,'','".AddSlashes(pg_result($resaco,0,'cp05_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1196,7196,'','".AddSlashes(pg_result($resaco,0,'cp05_codsubordinacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cp05_codlocalidades=null) { 
      $this->atualizacampos();
     $sql = " update ceplocalidades set ";
     $virgula = "";
     if(trim($this->cp05_codlocalidades)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp05_codlocalidades"])){ 
       $sql  .= $virgula." cp05_codlocalidades = $this->cp05_codlocalidades ";
       $virgula = ",";
       if(trim($this->cp05_codlocalidades) == null ){ 
         $this->erro_sql = " Campo Codigo da Localidade nao Informado.";
         $this->erro_campo = "cp05_codlocalidades";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp05_sigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp05_sigla"])){ 
       $sql  .= $virgula." cp05_sigla = '$this->cp05_sigla' ";
       $virgula = ",";
       if(trim($this->cp05_sigla) == null ){ 
         $this->erro_sql = " Campo Sigla Estado nao Informado.";
         $this->erro_campo = "cp05_sigla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp05_localidades)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp05_localidades"])){ 
       $sql  .= $virgula." cp05_localidades = '$this->cp05_localidades' ";
       $virgula = ",";
       if(trim($this->cp05_localidades) == null ){ 
         $this->erro_sql = " Campo Cadastro de Localidades nao Informado.";
         $this->erro_campo = "cp05_localidades";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp05_cepinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp05_cepinicial"])){ 
       $sql  .= $virgula." cp05_cepinicial = '$this->cp05_cepinicial' ";
       $virgula = ",";
       if(trim($this->cp05_cepinicial) == null ){ 
         $this->erro_sql = " Campo Cep inicial nao Informado.";
         $this->erro_campo = "cp05_cepinicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp05_cepfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp05_cepfinal"])){ 
       $sql  .= $virgula." cp05_cepfinal = '$this->cp05_cepfinal' ";
       $virgula = ",";
       if(trim($this->cp05_cepfinal) == null ){ 
         $this->erro_sql = " Campo Cep final nao Informado.";
         $this->erro_campo = "cp05_cepfinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp05_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp05_tipo"])){ 
       $sql  .= $virgula." cp05_tipo = '$this->cp05_tipo' ";
       $virgula = ",";
       if(trim($this->cp05_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "cp05_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp05_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp05_situacao"])){ 
       $sql  .= $virgula." cp05_situacao = '$this->cp05_situacao' ";
       $virgula = ",";
       if(trim($this->cp05_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "cp05_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp05_codsubordinacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp05_codsubordinacao"])){ 
       $sql  .= $virgula." cp05_codsubordinacao = $this->cp05_codsubordinacao ";
       $virgula = ",";
       if(trim($this->cp05_codsubordinacao) == null ){ 
         $this->erro_sql = " Campo Codigo Subordinação nao Informado.";
         $this->erro_campo = "cp05_codsubordinacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cp05_codlocalidades!=null){
       $sql .= " cp05_codlocalidades = $this->cp05_codlocalidades";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cp05_codlocalidades));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7189,'$this->cp05_codlocalidades','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp05_codlocalidades"]))
           $resac = db_query("insert into db_acount values($acount,1196,7189,'".AddSlashes(pg_result($resaco,$conresaco,'cp05_codlocalidades'))."','$this->cp05_codlocalidades',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp05_sigla"]))
           $resac = db_query("insert into db_acount values($acount,1196,7190,'".AddSlashes(pg_result($resaco,$conresaco,'cp05_sigla'))."','$this->cp05_sigla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp05_localidades"]))
           $resac = db_query("insert into db_acount values($acount,1196,7191,'".AddSlashes(pg_result($resaco,$conresaco,'cp05_localidades'))."','$this->cp05_localidades',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp05_cepinicial"]))
           $resac = db_query("insert into db_acount values($acount,1196,7192,'".AddSlashes(pg_result($resaco,$conresaco,'cp05_cepinicial'))."','$this->cp05_cepinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp05_cepfinal"]))
           $resac = db_query("insert into db_acount values($acount,1196,7193,'".AddSlashes(pg_result($resaco,$conresaco,'cp05_cepfinal'))."','$this->cp05_cepfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp05_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1196,7194,'".AddSlashes(pg_result($resaco,$conresaco,'cp05_tipo'))."','$this->cp05_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp05_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1196,7195,'".AddSlashes(pg_result($resaco,$conresaco,'cp05_situacao'))."','$this->cp05_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp05_codsubordinacao"]))
           $resac = db_query("insert into db_acount values($acount,1196,7196,'".AddSlashes(pg_result($resaco,$conresaco,'cp05_codsubordinacao'))."','$this->cp05_codsubordinacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Localidades nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cp05_codlocalidades;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Localidades nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cp05_codlocalidades;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cp05_codlocalidades;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cp05_codlocalidades=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cp05_codlocalidades));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7189,'$cp05_codlocalidades','E')");
         $resac = db_query("insert into db_acount values($acount,1196,7189,'','".AddSlashes(pg_result($resaco,$iresaco,'cp05_codlocalidades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1196,7190,'','".AddSlashes(pg_result($resaco,$iresaco,'cp05_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1196,7191,'','".AddSlashes(pg_result($resaco,$iresaco,'cp05_localidades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1196,7192,'','".AddSlashes(pg_result($resaco,$iresaco,'cp05_cepinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1196,7193,'','".AddSlashes(pg_result($resaco,$iresaco,'cp05_cepfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1196,7194,'','".AddSlashes(pg_result($resaco,$iresaco,'cp05_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1196,7195,'','".AddSlashes(pg_result($resaco,$iresaco,'cp05_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1196,7196,'','".AddSlashes(pg_result($resaco,$iresaco,'cp05_codsubordinacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ceplocalidades
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cp05_codlocalidades != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cp05_codlocalidades = $cp05_codlocalidades ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Localidades nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cp05_codlocalidades;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Localidades nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cp05_codlocalidades;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cp05_codlocalidades;
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
        $this->erro_sql   = "Record Vazio na Tabela:ceplocalidades";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $cp05_codlocalidades=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ceplocalidades ";
     $sql .= "      inner join cepestados  on  cepestados.cp03_sigla = ceplocalidades.cp05_sigla";
     $sql2 = "";
     if($dbwhere==""){
       if($cp05_codlocalidades!=null ){
         $sql2 .= " where ceplocalidades.cp05_codlocalidades = $cp05_codlocalidades "; 
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
   function sql_query_file ( $cp05_codlocalidades=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ceplocalidades ";
     $sql2 = "";
     if($dbwhere==""){
       if($cp05_codlocalidades!=null ){
         $sql2 .= " where ceplocalidades.cp05_codlocalidades = $cp05_codlocalidades "; 
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