<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: empenho
//CLASSE DA ENTIDADE empprestaitem
class cl_empprestaitem { 
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
   var $e46_codigo = 0; 
   var $e46_numemp = 0; 
   var $e46_nota = null; 
   var $e46_valor = 0; 
   var $e46_descr = null; 
   var $e46_id_usuario = 0; 
   var $e46_cnpj = null; 
   var $e46_cpf = null; 
   var $e46_nome = null; 
   var $e46_emppresta = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e46_codigo = int4 = Código 
                 e46_numemp = int4 = Número 
                 e46_nota = varchar(20) = Nota fiscal 
                 e46_valor = float4 = Valor 
                 e46_descr = text = Descrição 
                 e46_id_usuario = int4 = Cod. Usuário 
                 e46_cnpj = varchar(14) = CNPJ 
                 e46_cpf = varchar(11) = CPF 
                 e46_nome = varchar(80) = Nome 
                 e46_emppresta = int4 = Referência para emppresta 
                 ";
   //funcao construtor da classe 
   function cl_empprestaitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empprestaitem"); 
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
       $this->e46_codigo = ($this->e46_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["e46_codigo"]:$this->e46_codigo);
       $this->e46_numemp = ($this->e46_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e46_numemp"]:$this->e46_numemp);
       $this->e46_nota = ($this->e46_nota == ""?@$GLOBALS["HTTP_POST_VARS"]["e46_nota"]:$this->e46_nota);
       $this->e46_valor = ($this->e46_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["e46_valor"]:$this->e46_valor);
       $this->e46_descr = ($this->e46_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["e46_descr"]:$this->e46_descr);
       $this->e46_id_usuario = ($this->e46_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["e46_id_usuario"]:$this->e46_id_usuario);
       $this->e46_cnpj = ($this->e46_cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["e46_cnpj"]:$this->e46_cnpj);
       $this->e46_cpf = ($this->e46_cpf == ""?@$GLOBALS["HTTP_POST_VARS"]["e46_cpf"]:$this->e46_cpf);
       $this->e46_nome = ($this->e46_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["e46_nome"]:$this->e46_nome);
       $this->e46_emppresta = ($this->e46_emppresta == ""?@$GLOBALS["HTTP_POST_VARS"]["e46_emppresta"]:$this->e46_emppresta);
     }else{
       $this->e46_codigo = ($this->e46_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["e46_codigo"]:$this->e46_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($e46_codigo){ 
      $this->atualizacampos();
     if($this->e46_numemp == null ){ 
       $this->erro_sql = " Campo Número não informado.";
       $this->erro_campo = "e46_numemp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e46_nota == null ){ 
       $this->erro_sql = " Campo Nota fiscal não informado.";
       $this->erro_campo = "e46_nota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e46_valor == null ){ 
       $this->erro_sql = " Campo Valor não informado.";
       $this->erro_campo = "e46_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e46_descr == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "e46_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e46_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário não informado.";
       $this->erro_campo = "e46_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e46_nome == null ){ 
       $this->erro_sql = " Campo Nome não informado.";
       $this->erro_campo = "e46_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e46_emppresta == null ){ 
       $this->erro_sql = " Campo Referência para emppresta não informado.";
       $this->erro_campo = "e46_emppresta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e46_codigo == "" || $e46_codigo == null ){
       $result = db_query("select nextval('empprestaitem_e46_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empprestaitem_e46_codigo_seq do campo: e46_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e46_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from empprestaitem_e46_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $e46_codigo)){
         $this->erro_sql = " Campo e46_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e46_codigo = $e46_codigo; 
       }
     }
     if(($this->e46_codigo == null) || ($this->e46_codigo == "") ){ 
       $this->erro_sql = " Campo e46_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empprestaitem(
                                       e46_codigo 
                                      ,e46_numemp 
                                      ,e46_nota 
                                      ,e46_valor 
                                      ,e46_descr 
                                      ,e46_id_usuario 
                                      ,e46_cnpj 
                                      ,e46_cpf 
                                      ,e46_nome 
                                      ,e46_emppresta 
                       )
                values (
                                $this->e46_codigo 
                               ,$this->e46_numemp 
                               ,'$this->e46_nota' 
                               ,$this->e46_valor 
                               ,'$this->e46_descr' 
                               ,$this->e46_id_usuario 
                               ,'$this->e46_cnpj' 
                               ,'$this->e46_cpf' 
                               ,'$this->e46_nome' 
                               ,$this->e46_emppresta 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Prestação de itens ($this->e46_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Prestação de itens já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Prestação de itens ($this->e46_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e46_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e46_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6347,'$this->e46_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1037,6347,'','".AddSlashes(pg_result($resaco,0,'e46_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1037,6346,'','".AddSlashes(pg_result($resaco,0,'e46_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1037,6348,'','".AddSlashes(pg_result($resaco,0,'e46_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1037,6349,'','".AddSlashes(pg_result($resaco,0,'e46_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1037,6350,'','".AddSlashes(pg_result($resaco,0,'e46_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1037,6351,'','".AddSlashes(pg_result($resaco,0,'e46_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1037,6352,'','".AddSlashes(pg_result($resaco,0,'e46_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1037,6353,'','".AddSlashes(pg_result($resaco,0,'e46_cpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1037,6354,'','".AddSlashes(pg_result($resaco,0,'e46_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1037,20272,'','".AddSlashes(pg_result($resaco,0,'e46_emppresta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e46_codigo=null) { 
      $this->atualizacampos();
     $sql = " update empprestaitem set ";
     $virgula = "";
     if(trim($this->e46_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e46_codigo"])){ 
       $sql  .= $virgula." e46_codigo = $this->e46_codigo ";
       $virgula = ",";
       if(trim($this->e46_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "e46_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e46_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e46_numemp"])){ 
       $sql  .= $virgula." e46_numemp = $this->e46_numemp ";
       $virgula = ",";
       if(trim($this->e46_numemp) == null ){ 
         $this->erro_sql = " Campo Número não informado.";
         $this->erro_campo = "e46_numemp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e46_nota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e46_nota"])){ 
       $sql  .= $virgula." e46_nota = '$this->e46_nota' ";
       $virgula = ",";
       if(trim($this->e46_nota) == null ){ 
         $this->erro_sql = " Campo Nota fiscal não informado.";
         $this->erro_campo = "e46_nota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e46_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e46_valor"])){ 
       $sql  .= $virgula." e46_valor = $this->e46_valor ";
       $virgula = ",";
       if(trim($this->e46_valor) == null ){ 
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "e46_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e46_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e46_descr"])){ 
       $sql  .= $virgula." e46_descr = '$this->e46_descr' ";
       $virgula = ",";
       if(trim($this->e46_descr) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "e46_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e46_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e46_id_usuario"])){ 
       $sql  .= $virgula." e46_id_usuario = $this->e46_id_usuario ";
       $virgula = ",";
       if(trim($this->e46_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário não informado.";
         $this->erro_campo = "e46_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e46_cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e46_cnpj"])){ 
       $sql  .= $virgula." e46_cnpj = '$this->e46_cnpj' ";
       $virgula = ",";
     }
     if(trim($this->e46_cpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e46_cpf"])){ 
       $sql  .= $virgula." e46_cpf = '$this->e46_cpf' ";
       $virgula = ",";
     }
     if(trim($this->e46_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e46_nome"])){ 
       $sql  .= $virgula." e46_nome = '$this->e46_nome' ";
       $virgula = ",";
       if(trim($this->e46_nome) == null ){ 
         $this->erro_sql = " Campo Nome não informado.";
         $this->erro_campo = "e46_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e46_emppresta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e46_emppresta"])){ 
       $sql  .= $virgula." e46_emppresta = $this->e46_emppresta ";
       $virgula = ",";
       if(trim($this->e46_emppresta) == null ){ 
         $this->erro_sql = " Campo Referência para emppresta não informado.";
         $this->erro_campo = "e46_emppresta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e46_codigo!=null){
       $sql .= " e46_codigo = $this->e46_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e46_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,6347,'$this->e46_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e46_codigo"]) || $this->e46_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1037,6347,'".AddSlashes(pg_result($resaco,$conresaco,'e46_codigo'))."','$this->e46_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e46_numemp"]) || $this->e46_numemp != "")
             $resac = db_query("insert into db_acount values($acount,1037,6346,'".AddSlashes(pg_result($resaco,$conresaco,'e46_numemp'))."','$this->e46_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e46_nota"]) || $this->e46_nota != "")
             $resac = db_query("insert into db_acount values($acount,1037,6348,'".AddSlashes(pg_result($resaco,$conresaco,'e46_nota'))."','$this->e46_nota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e46_valor"]) || $this->e46_valor != "")
             $resac = db_query("insert into db_acount values($acount,1037,6349,'".AddSlashes(pg_result($resaco,$conresaco,'e46_valor'))."','$this->e46_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e46_descr"]) || $this->e46_descr != "")
             $resac = db_query("insert into db_acount values($acount,1037,6350,'".AddSlashes(pg_result($resaco,$conresaco,'e46_descr'))."','$this->e46_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e46_id_usuario"]) || $this->e46_id_usuario != "")
             $resac = db_query("insert into db_acount values($acount,1037,6351,'".AddSlashes(pg_result($resaco,$conresaco,'e46_id_usuario'))."','$this->e46_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e46_cnpj"]) || $this->e46_cnpj != "")
             $resac = db_query("insert into db_acount values($acount,1037,6352,'".AddSlashes(pg_result($resaco,$conresaco,'e46_cnpj'))."','$this->e46_cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e46_cpf"]) || $this->e46_cpf != "")
             $resac = db_query("insert into db_acount values($acount,1037,6353,'".AddSlashes(pg_result($resaco,$conresaco,'e46_cpf'))."','$this->e46_cpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e46_nome"]) || $this->e46_nome != "")
             $resac = db_query("insert into db_acount values($acount,1037,6354,'".AddSlashes(pg_result($resaco,$conresaco,'e46_nome'))."','$this->e46_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e46_emppresta"]) || $this->e46_emppresta != "")
             $resac = db_query("insert into db_acount values($acount,1037,20272,'".AddSlashes(pg_result($resaco,$conresaco,'e46_emppresta'))."','$this->e46_emppresta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prestação de itens nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e46_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prestação de itens nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e46_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e46_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e46_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($e46_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,6347,'$e46_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1037,6347,'','".AddSlashes(pg_result($resaco,$iresaco,'e46_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1037,6346,'','".AddSlashes(pg_result($resaco,$iresaco,'e46_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1037,6348,'','".AddSlashes(pg_result($resaco,$iresaco,'e46_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1037,6349,'','".AddSlashes(pg_result($resaco,$iresaco,'e46_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1037,6350,'','".AddSlashes(pg_result($resaco,$iresaco,'e46_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1037,6351,'','".AddSlashes(pg_result($resaco,$iresaco,'e46_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1037,6352,'','".AddSlashes(pg_result($resaco,$iresaco,'e46_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1037,6353,'','".AddSlashes(pg_result($resaco,$iresaco,'e46_cpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1037,6354,'','".AddSlashes(pg_result($resaco,$iresaco,'e46_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1037,20272,'','".AddSlashes(pg_result($resaco,$iresaco,'e46_emppresta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from empprestaitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e46_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e46_codigo = $e46_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prestação de itens nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e46_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prestação de itens nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e46_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e46_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:empprestaitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $e46_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empprestaitem ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empprestaitem.e46_id_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empprestaitem.e46_numemp";
     $sql .= "      inner join emppresta  on  emppresta.e45_sequencial = empprestaitem.e46_emppresta";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
     $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
     $sql .= "      inner join empprestatip  on  empprestatip.e44_tipo = emppresta.e45_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if($e46_codigo!=null ){
         $sql2 .= " where empprestaitem.e46_codigo = $e46_codigo "; 
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
   function sql_query_file ( $e46_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empprestaitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($e46_codigo!=null ){
         $sql2 .= " where empprestaitem.e46_codigo = $e46_codigo "; 
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
   function sql_query_emp ( $e46_numemp=null,$e46_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empprestaitem ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empprestaitem.e46_id_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empprestaitem.e46_numemp";
     $sql .= "      inner join emppresta  on  emppresta.e45_numemp = empprestaitem.e46_numemp";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      inner join empprestatip  on  empprestatip.e44_tipo = emppresta.e45_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if($e46_numemp!=null ){
         $sql2 .= " where empprestaitem.e46_numemp = $e46_numemp ";
       }
       if($e46_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empprestaitem.e46_codigo = $e46_codigo ";
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