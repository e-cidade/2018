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

//MODULO: pessoal
//CLASSE DA ENTIDADE cad_afast
class cl_cad_afast { 
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
   var $r68_anousu = 0; 
   var $r68_mesusu = 0; 
   var $r68_codigo = 0; 
   var $r68_descr = null; 
   var $r68_vt = 'f'; 
   var $r68_salario = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r68_anousu = float4 = Ano do Exercício 
                 r68_mesusu = float4 = Mês do Exercício 
                 r68_codigo = float4 = Código do Afastamento 
                 r68_descr = varchar(40) = Descrição 
                 r68_vt = bool = Vale Transporte 
                 r68_salario = bool = Salário 
                 ";
   //funcao construtor da classe 
   function cl_cad_afast() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cad_afast"); 
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
       $this->r68_anousu = ($this->r68_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r68_anousu"]:$this->r68_anousu);
       $this->r68_mesusu = ($this->r68_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r68_mesusu"]:$this->r68_mesusu);
       $this->r68_codigo = ($this->r68_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r68_codigo"]:$this->r68_codigo);
       $this->r68_descr = ($this->r68_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["r68_descr"]:$this->r68_descr);
       $this->r68_vt = ($this->r68_vt == "f"?@$GLOBALS["HTTP_POST_VARS"]["r68_vt"]:$this->r68_vt);
       $this->r68_salario = ($this->r68_salario == "f"?@$GLOBALS["HTTP_POST_VARS"]["r68_salario"]:$this->r68_salario);
     }else{
       $this->r68_anousu = ($this->r68_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r68_anousu"]:$this->r68_anousu);
       $this->r68_mesusu = ($this->r68_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r68_mesusu"]:$this->r68_mesusu);
       $this->r68_codigo = ($this->r68_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r68_codigo"]:$this->r68_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($r68_anousu,$r68_mesusu,$r68_codigo){ 
      $this->atualizacampos();
     if($this->r68_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "r68_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r68_vt == null ){ 
       $this->erro_sql = " Campo Vale Transporte nao Informado.";
       $this->erro_campo = "r68_vt";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r68_salario == null ){ 
       $this->erro_sql = " Campo Salário nao Informado.";
       $this->erro_campo = "r68_salario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r68_anousu = $r68_anousu; 
       $this->r68_mesusu = $r68_mesusu; 
       $this->r68_codigo = $r68_codigo; 
     if(($this->r68_anousu == null) || ($this->r68_anousu == "") ){ 
       $this->erro_sql = " Campo r68_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r68_mesusu == null) || ($this->r68_mesusu == "") ){ 
       $this->erro_sql = " Campo r68_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r68_codigo == null) || ($this->r68_codigo == "") ){ 
       $this->erro_sql = " Campo r68_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cad_afast(
                                       r68_anousu 
                                      ,r68_mesusu 
                                      ,r68_codigo 
                                      ,r68_descr 
                                      ,r68_vt 
                                      ,r68_salario 
                       )
                values (
                                $this->r68_anousu 
                               ,$this->r68_mesusu 
                               ,$this->r68_codigo 
                               ,'$this->r68_descr' 
                               ,'$this->r68_vt' 
                               ,'$this->r68_salario' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Afastamentos Informativos ($this->r68_anousu."-".$this->r68_mesusu."-".$this->r68_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Afastamentos Informativos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Afastamentos Informativos ($this->r68_anousu."-".$this->r68_mesusu."-".$this->r68_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r68_anousu."-".$this->r68_mesusu."-".$this->r68_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r68_anousu,$this->r68_mesusu,$this->r68_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4556,'$this->r68_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4557,'$this->r68_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4558,'$this->r68_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,603,4556,'','".AddSlashes(pg_result($resaco,0,'r68_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,603,4557,'','".AddSlashes(pg_result($resaco,0,'r68_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,603,4558,'','".AddSlashes(pg_result($resaco,0,'r68_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,603,4559,'','".AddSlashes(pg_result($resaco,0,'r68_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,603,4560,'','".AddSlashes(pg_result($resaco,0,'r68_vt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,603,4561,'','".AddSlashes(pg_result($resaco,0,'r68_salario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r68_anousu=null,$r68_mesusu=null,$r68_codigo=null) { 
      $this->atualizacampos();
     $sql = " update cad_afast set ";
     $virgula = "";
     if(trim($this->r68_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r68_anousu"])){ 
       $sql  .= $virgula." r68_anousu = $this->r68_anousu ";
       $virgula = ",";
       if(trim($this->r68_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercício nao Informado.";
         $this->erro_campo = "r68_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r68_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r68_mesusu"])){ 
       $sql  .= $virgula." r68_mesusu = $this->r68_mesusu ";
       $virgula = ",";
       if(trim($this->r68_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês do Exercício nao Informado.";
         $this->erro_campo = "r68_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r68_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r68_codigo"])){ 
       $sql  .= $virgula." r68_codigo = $this->r68_codigo ";
       $virgula = ",";
       if(trim($this->r68_codigo) == null ){ 
         $this->erro_sql = " Campo Código do Afastamento nao Informado.";
         $this->erro_campo = "r68_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r68_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r68_descr"])){ 
       $sql  .= $virgula." r68_descr = '$this->r68_descr' ";
       $virgula = ",";
       if(trim($this->r68_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "r68_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r68_vt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r68_vt"])){ 
       $sql  .= $virgula." r68_vt = '$this->r68_vt' ";
       $virgula = ",";
       if(trim($this->r68_vt) == null ){ 
         $this->erro_sql = " Campo Vale Transporte nao Informado.";
         $this->erro_campo = "r68_vt";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r68_salario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r68_salario"])){ 
       $sql  .= $virgula." r68_salario = '$this->r68_salario' ";
       $virgula = ",";
       if(trim($this->r68_salario) == null ){ 
         $this->erro_sql = " Campo Salário nao Informado.";
         $this->erro_campo = "r68_salario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r68_anousu!=null){
       $sql .= " r68_anousu = $this->r68_anousu";
     }
     if($r68_mesusu!=null){
       $sql .= " and  r68_mesusu = $this->r68_mesusu";
     }
     if($r68_codigo!=null){
       $sql .= " and  r68_codigo = $this->r68_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r68_anousu,$this->r68_mesusu,$this->r68_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4556,'$this->r68_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4557,'$this->r68_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4558,'$this->r68_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r68_anousu"]))
           $resac = db_query("insert into db_acount values($acount,603,4556,'".AddSlashes(pg_result($resaco,$conresaco,'r68_anousu'))."','$this->r68_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r68_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,603,4557,'".AddSlashes(pg_result($resaco,$conresaco,'r68_mesusu'))."','$this->r68_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r68_codigo"]))
           $resac = db_query("insert into db_acount values($acount,603,4558,'".AddSlashes(pg_result($resaco,$conresaco,'r68_codigo'))."','$this->r68_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r68_descr"]))
           $resac = db_query("insert into db_acount values($acount,603,4559,'".AddSlashes(pg_result($resaco,$conresaco,'r68_descr'))."','$this->r68_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r68_vt"]))
           $resac = db_query("insert into db_acount values($acount,603,4560,'".AddSlashes(pg_result($resaco,$conresaco,'r68_vt'))."','$this->r68_vt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r68_salario"]))
           $resac = db_query("insert into db_acount values($acount,603,4561,'".AddSlashes(pg_result($resaco,$conresaco,'r68_salario'))."','$this->r68_salario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Afastamentos Informativos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r68_anousu."-".$this->r68_mesusu."-".$this->r68_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Afastamentos Informativos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r68_anousu."-".$this->r68_mesusu."-".$this->r68_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r68_anousu."-".$this->r68_mesusu."-".$this->r68_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r68_anousu=null,$r68_mesusu=null,$r68_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r68_anousu,$r68_mesusu,$r68_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4556,'$r68_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4557,'$r68_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4558,'$r68_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,603,4556,'','".AddSlashes(pg_result($resaco,$iresaco,'r68_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,603,4557,'','".AddSlashes(pg_result($resaco,$iresaco,'r68_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,603,4558,'','".AddSlashes(pg_result($resaco,$iresaco,'r68_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,603,4559,'','".AddSlashes(pg_result($resaco,$iresaco,'r68_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,603,4560,'','".AddSlashes(pg_result($resaco,$iresaco,'r68_vt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,603,4561,'','".AddSlashes(pg_result($resaco,$iresaco,'r68_salario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cad_afast
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r68_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r68_anousu = $r68_anousu ";
        }
        if($r68_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r68_mesusu = $r68_mesusu ";
        }
        if($r68_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r68_codigo = $r68_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Afastamentos Informativos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r68_anousu."-".$r68_mesusu."-".$r68_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Afastamentos Informativos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r68_anousu."-".$r68_mesusu."-".$r68_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r68_anousu."-".$r68_mesusu."-".$r68_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:cad_afast";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>