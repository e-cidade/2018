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

//MODULO: saude
//CLASSE DA ENTIDADE sau_formaorganizacao
class cl_sau_formaorganizacao { 
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
   var $sd62_i_codigo = 0; 
   var $sd62_i_grupo = 0; 
   var $sd62_i_subgrupo = 0; 
   var $sd62_c_formaorganizacao = null; 
   var $sd62_c_nome = null; 
   var $sd62_i_anocomp = 0; 
   var $sd62_i_mescomp = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd62_i_codigo = int8 = Código 
                 sd62_i_grupo = int8 = Grupo 
                 sd62_i_subgrupo = int8 = Sub Grupo 
                 sd62_c_formaorganizacao = varchar(2) = Forma de Organização 
                 sd62_c_nome = varchar(100) = Nome 
                 sd62_i_anocomp = int4 = Ano 
                 sd62_i_mescomp = int4 = Mes 
                 ";
   //funcao construtor da classe 
   function cl_sau_formaorganizacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_formaorganizacao"); 
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
       $this->sd62_i_codigo = ($this->sd62_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd62_i_codigo"]:$this->sd62_i_codigo);
       $this->sd62_i_grupo = ($this->sd62_i_grupo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd62_i_grupo"]:$this->sd62_i_grupo);
       $this->sd62_i_subgrupo = ($this->sd62_i_subgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd62_i_subgrupo"]:$this->sd62_i_subgrupo);
       $this->sd62_c_formaorganizacao = ($this->sd62_c_formaorganizacao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd62_c_formaorganizacao"]:$this->sd62_c_formaorganizacao);
       $this->sd62_c_nome = ($this->sd62_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["sd62_c_nome"]:$this->sd62_c_nome);
       $this->sd62_i_anocomp = ($this->sd62_i_anocomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd62_i_anocomp"]:$this->sd62_i_anocomp);
       $this->sd62_i_mescomp = ($this->sd62_i_mescomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd62_i_mescomp"]:$this->sd62_i_mescomp);
     }else{
       $this->sd62_i_codigo = ($this->sd62_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd62_i_codigo"]:$this->sd62_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd62_i_codigo){ 
      $this->atualizacampos();
     if($this->sd62_i_grupo == null ){ 
       $this->erro_sql = " Campo Grupo nao Informado.";
       $this->erro_campo = "sd62_i_grupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd62_i_subgrupo == null ){ 
       $this->erro_sql = " Campo Sub Grupo nao Informado.";
       $this->erro_campo = "sd62_i_subgrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd62_c_formaorganizacao == null ){ 
       $this->erro_sql = " Campo Forma de Organização nao Informado.";
       $this->erro_campo = "sd62_c_formaorganizacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd62_c_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "sd62_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd62_i_anocomp == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "sd62_i_anocomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd62_i_mescomp == null ){ 
       $this->erro_sql = " Campo Mes nao Informado.";
       $this->erro_campo = "sd62_i_mescomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd62_i_codigo == "" || $sd62_i_codigo == null ){
       $result = db_query("select nextval('sau_formaorganizacao_sd62_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_formaorganizacao_sd62_i_codigo_seq do campo: sd62_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd62_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_formaorganizacao_sd62_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd62_i_codigo)){
         $this->erro_sql = " Campo sd62_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd62_i_codigo = $sd62_i_codigo; 
       }
     }
     if(($this->sd62_i_codigo == null) || ($this->sd62_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd62_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_formaorganizacao(
                                       sd62_i_codigo 
                                      ,sd62_i_grupo 
                                      ,sd62_i_subgrupo 
                                      ,sd62_c_formaorganizacao 
                                      ,sd62_c_nome 
                                      ,sd62_i_anocomp 
                                      ,sd62_i_mescomp 
                       )
                values (
                                $this->sd62_i_codigo 
                               ,$this->sd62_i_grupo 
                               ,$this->sd62_i_subgrupo 
                               ,'$this->sd62_c_formaorganizacao' 
                               ,'$this->sd62_c_nome' 
                               ,$this->sd62_i_anocomp 
                               ,$this->sd62_i_mescomp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Forma de Organização ($this->sd62_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Forma de Organização já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Forma de Organização ($this->sd62_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd62_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd62_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11505,'$this->sd62_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1982,11505,'','".AddSlashes(pg_result($resaco,0,'sd62_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1982,11506,'','".AddSlashes(pg_result($resaco,0,'sd62_i_grupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1982,11507,'','".AddSlashes(pg_result($resaco,0,'sd62_i_subgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1982,11508,'','".AddSlashes(pg_result($resaco,0,'sd62_c_formaorganizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1982,11509,'','".AddSlashes(pg_result($resaco,0,'sd62_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1982,11510,'','".AddSlashes(pg_result($resaco,0,'sd62_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1982,11511,'','".AddSlashes(pg_result($resaco,0,'sd62_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd62_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_formaorganizacao set ";
     $virgula = "";
     if(trim($this->sd62_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd62_i_codigo"])){ 
       $sql  .= $virgula." sd62_i_codigo = $this->sd62_i_codigo ";
       $virgula = ",";
       if(trim($this->sd62_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd62_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd62_i_grupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd62_i_grupo"])){ 
       $sql  .= $virgula." sd62_i_grupo = $this->sd62_i_grupo ";
       $virgula = ",";
       if(trim($this->sd62_i_grupo) == null ){ 
         $this->erro_sql = " Campo Grupo nao Informado.";
         $this->erro_campo = "sd62_i_grupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd62_i_subgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd62_i_subgrupo"])){ 
       $sql  .= $virgula." sd62_i_subgrupo = $this->sd62_i_subgrupo ";
       $virgula = ",";
       if(trim($this->sd62_i_subgrupo) == null ){ 
         $this->erro_sql = " Campo Sub Grupo nao Informado.";
         $this->erro_campo = "sd62_i_subgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd62_c_formaorganizacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd62_c_formaorganizacao"])){ 
       $sql  .= $virgula." sd62_c_formaorganizacao = '$this->sd62_c_formaorganizacao' ";
       $virgula = ",";
       if(trim($this->sd62_c_formaorganizacao) == null ){ 
         $this->erro_sql = " Campo Forma de Organização nao Informado.";
         $this->erro_campo = "sd62_c_formaorganizacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd62_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd62_c_nome"])){ 
       $sql  .= $virgula." sd62_c_nome = '$this->sd62_c_nome' ";
       $virgula = ",";
       if(trim($this->sd62_c_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "sd62_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd62_i_anocomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd62_i_anocomp"])){ 
       $sql  .= $virgula." sd62_i_anocomp = $this->sd62_i_anocomp ";
       $virgula = ",";
       if(trim($this->sd62_i_anocomp) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "sd62_i_anocomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd62_i_mescomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd62_i_mescomp"])){ 
       $sql  .= $virgula." sd62_i_mescomp = $this->sd62_i_mescomp ";
       $virgula = ",";
       if(trim($this->sd62_i_mescomp) == null ){ 
         $this->erro_sql = " Campo Mes nao Informado.";
         $this->erro_campo = "sd62_i_mescomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd62_i_codigo!=null){
       $sql .= " sd62_i_codigo = $this->sd62_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd62_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11505,'$this->sd62_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd62_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1982,11505,'".AddSlashes(pg_result($resaco,$conresaco,'sd62_i_codigo'))."','$this->sd62_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd62_i_grupo"]))
           $resac = db_query("insert into db_acount values($acount,1982,11506,'".AddSlashes(pg_result($resaco,$conresaco,'sd62_i_grupo'))."','$this->sd62_i_grupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd62_i_subgrupo"]))
           $resac = db_query("insert into db_acount values($acount,1982,11507,'".AddSlashes(pg_result($resaco,$conresaco,'sd62_i_subgrupo'))."','$this->sd62_i_subgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd62_c_formaorganizacao"]))
           $resac = db_query("insert into db_acount values($acount,1982,11508,'".AddSlashes(pg_result($resaco,$conresaco,'sd62_c_formaorganizacao'))."','$this->sd62_c_formaorganizacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd62_c_nome"]))
           $resac = db_query("insert into db_acount values($acount,1982,11509,'".AddSlashes(pg_result($resaco,$conresaco,'sd62_c_nome'))."','$this->sd62_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd62_i_anocomp"]))
           $resac = db_query("insert into db_acount values($acount,1982,11510,'".AddSlashes(pg_result($resaco,$conresaco,'sd62_i_anocomp'))."','$this->sd62_i_anocomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd62_i_mescomp"]))
           $resac = db_query("insert into db_acount values($acount,1982,11511,'".AddSlashes(pg_result($resaco,$conresaco,'sd62_i_mescomp'))."','$this->sd62_i_mescomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Forma de Organização nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd62_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Forma de Organização nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd62_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd62_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd62_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd62_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11505,'$sd62_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1982,11505,'','".AddSlashes(pg_result($resaco,$iresaco,'sd62_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1982,11506,'','".AddSlashes(pg_result($resaco,$iresaco,'sd62_i_grupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1982,11507,'','".AddSlashes(pg_result($resaco,$iresaco,'sd62_i_subgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1982,11508,'','".AddSlashes(pg_result($resaco,$iresaco,'sd62_c_formaorganizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1982,11509,'','".AddSlashes(pg_result($resaco,$iresaco,'sd62_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1982,11510,'','".AddSlashes(pg_result($resaco,$iresaco,'sd62_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1982,11511,'','".AddSlashes(pg_result($resaco,$iresaco,'sd62_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_formaorganizacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd62_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd62_i_codigo = $sd62_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Forma de Organização nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd62_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Forma de Organização nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd62_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd62_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_formaorganizacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd62_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_formaorganizacao ";
     $sql .= "      inner join sau_grupo    as a on  a.sd60_i_codigo = sau_formaorganizacao.sd62_i_grupo";
     $sql .= "      inner join sau_subgrupo      on  sau_subgrupo.sd61_i_codigo = sau_formaorganizacao.sd62_i_subgrupo";
     $sql .= "      inner join sau_grupo    as b on  b.sd60_i_codigo = sau_subgrupo.sd61_i_grupo";
     $sql2 = "";
     if($dbwhere==""){
       if($sd62_i_codigo!=null ){
         $sql2 .= " where sau_formaorganizacao.sd62_i_codigo = $sd62_i_codigo ";
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
   function sql_query_file ( $sd62_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_formaorganizacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd62_i_codigo!=null ){
         $sql2 .= " where sau_formaorganizacao.sd62_i_codigo = $sd62_i_codigo ";
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

  function sql_query2 ( $sd62_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_formaorganizacao ";
     $sql .= "      inner join sau_grupo  on  sau_grupo.sd60_i_codigo = sau_formaorganizacao.sd62_i_grupo";
     $sql .= "      inner join sau_subgrupo      on  sau_subgrupo.sd61_i_codigo = sau_formaorganizacao.sd62_i_subgrupo";
     $sql2 = "";
     if($dbwhere==""){
       if($sd62_i_codigo!=null ){
         $sql2 .= " where sau_formaorganizacao.sd62_i_codigo = $sd62_i_codigo ";
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