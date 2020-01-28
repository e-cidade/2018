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

//MODULO: pessoal
//CLASSE DA ENTIDADE pesdiver
class cl_pesdiver { 
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
   var $r07_anousu = 0; 
   var $r07_mesusu = 0; 
   var $r07_codigo = null; 
   var $r07_descr = null; 
   var $r07_valor = 0; 
   var $r07_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r07_anousu = int4 = Ano 
                 r07_mesusu = int4 = Mês 
                 r07_codigo = char(4) = Código 
                 r07_descr = char(30) = Descrição 
                 r07_valor = float8 = Valor 
                 r07_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_pesdiver() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pesdiver"); 
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
       $this->r07_anousu = ($this->r07_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r07_anousu"]:$this->r07_anousu);
       $this->r07_mesusu = ($this->r07_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r07_mesusu"]:$this->r07_mesusu);
       $this->r07_codigo = ($this->r07_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r07_codigo"]:$this->r07_codigo);
       $this->r07_descr = ($this->r07_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["r07_descr"]:$this->r07_descr);
       $this->r07_valor = ($this->r07_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r07_valor"]:$this->r07_valor);
       $this->r07_instit = ($this->r07_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r07_instit"]:$this->r07_instit);
     }else{
       $this->r07_anousu = ($this->r07_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r07_anousu"]:$this->r07_anousu);
       $this->r07_mesusu = ($this->r07_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r07_mesusu"]:$this->r07_mesusu);
       $this->r07_codigo = ($this->r07_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r07_codigo"]:$this->r07_codigo);
       $this->r07_instit = ($this->r07_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r07_instit"]:$this->r07_instit);
     }
   }
   // funcao para inclusao
   function incluir ($r07_anousu,$r07_mesusu,$r07_codigo,$r07_instit){ 
      $this->atualizacampos();
     if($this->r07_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "r07_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r07_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "r07_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r07_anousu = $r07_anousu; 
       $this->r07_mesusu = $r07_mesusu; 
       $this->r07_codigo = $r07_codigo; 
       $this->r07_instit = $r07_instit; 
     if(($this->r07_anousu == null) || ($this->r07_anousu == "") ){ 
       $this->erro_sql = " Campo r07_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r07_mesusu == null) || ($this->r07_mesusu == "") ){ 
       $this->erro_sql = " Campo r07_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r07_codigo == null) || ($this->r07_codigo == "") ){ 
       $this->erro_sql = " Campo r07_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r07_instit == null) || ($this->r07_instit == "") ){ 
       $this->erro_sql = " Campo r07_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pesdiver(
                                       r07_anousu 
                                      ,r07_mesusu 
                                      ,r07_codigo 
                                      ,r07_descr 
                                      ,r07_valor 
                                      ,r07_instit 
                       )
                values (
                                $this->r07_anousu 
                               ,$this->r07_mesusu 
                               ,'$this->r07_codigo' 
                               ,'$this->r07_descr' 
                               ,$this->r07_valor 
                               ,$this->r07_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Diversos ($this->r07_anousu."-".$this->r07_mesusu."-".$this->r07_codigo."-".$this->r07_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Diversos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Diversos ($this->r07_anousu."-".$this->r07_mesusu."-".$this->r07_codigo."-".$this->r07_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r07_anousu."-".$this->r07_mesusu."-".$this->r07_codigo."-".$this->r07_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r07_anousu,$this->r07_mesusu,$this->r07_codigo,$this->r07_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4130,'$this->r07_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4131,'$this->r07_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4132,'$this->r07_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,7473,'$this->r07_instit','I')");
       $resac = db_query("insert into db_acount values($acount,571,4130,'','".AddSlashes(pg_result($resaco,0,'r07_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,571,4131,'','".AddSlashes(pg_result($resaco,0,'r07_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,571,4132,'','".AddSlashes(pg_result($resaco,0,'r07_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,571,4133,'','".AddSlashes(pg_result($resaco,0,'r07_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,571,4134,'','".AddSlashes(pg_result($resaco,0,'r07_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,571,7473,'','".AddSlashes(pg_result($resaco,0,'r07_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r07_anousu=null,$r07_mesusu=null,$r07_codigo=null,$r07_instit=null) { 
      $this->atualizacampos();
     $sql = " update pesdiver set ";
     $virgula = "";
     if(trim($this->r07_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r07_anousu"])){ 
       $sql  .= $virgula." r07_anousu = $this->r07_anousu ";
       $virgula = ",";
       if(trim($this->r07_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "r07_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r07_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r07_mesusu"])){ 
       $sql  .= $virgula." r07_mesusu = $this->r07_mesusu ";
       $virgula = ",";
       if(trim($this->r07_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "r07_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r07_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r07_codigo"])){ 
       $sql  .= $virgula." r07_codigo = '$this->r07_codigo' ";
       $virgula = ",";
       if(trim($this->r07_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "r07_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r07_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r07_descr"])){ 
       $sql  .= $virgula." r07_descr = '$this->r07_descr' ";
       $virgula = ",";
       if(trim($this->r07_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "r07_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r07_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r07_valor"])){ 
       $sql  .= $virgula." r07_valor = $this->r07_valor ";
       $virgula = ",";
       if(trim($this->r07_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "r07_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r07_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r07_instit"])){ 
       $sql  .= $virgula." r07_instit = $this->r07_instit ";
       $virgula = ",";
       if(trim($this->r07_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "r07_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r07_anousu!=null){
       $sql .= " r07_anousu = $this->r07_anousu";
     }
     if($r07_mesusu!=null){
       $sql .= " and  r07_mesusu = $this->r07_mesusu";
     }
     if($r07_codigo!=null){
       $sql .= " and  r07_codigo = '$this->r07_codigo'";
     }
     if($r07_instit!=null){
       $sql .= " and  r07_instit = $this->r07_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r07_anousu,$this->r07_mesusu,$this->r07_codigo,$this->r07_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4130,'$this->r07_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4131,'$this->r07_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4132,'$this->r07_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,7473,'$this->r07_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r07_anousu"]))
           $resac = db_query("insert into db_acount values($acount,571,4130,'".AddSlashes(pg_result($resaco,$conresaco,'r07_anousu'))."','$this->r07_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r07_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,571,4131,'".AddSlashes(pg_result($resaco,$conresaco,'r07_mesusu'))."','$this->r07_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r07_codigo"]))
           $resac = db_query("insert into db_acount values($acount,571,4132,'".AddSlashes(pg_result($resaco,$conresaco,'r07_codigo'))."','$this->r07_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r07_descr"]))
           $resac = db_query("insert into db_acount values($acount,571,4133,'".AddSlashes(pg_result($resaco,$conresaco,'r07_descr'))."','$this->r07_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r07_valor"]))
           $resac = db_query("insert into db_acount values($acount,571,4134,'".AddSlashes(pg_result($resaco,$conresaco,'r07_valor'))."','$this->r07_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r07_instit"]))
           $resac = db_query("insert into db_acount values($acount,571,7473,'".AddSlashes(pg_result($resaco,$conresaco,'r07_instit'))."','$this->r07_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Diversos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r07_anousu."-".$this->r07_mesusu."-".$this->r07_codigo."-".$this->r07_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Diversos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r07_anousu."-".$this->r07_mesusu."-".$this->r07_codigo."-".$this->r07_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r07_anousu."-".$this->r07_mesusu."-".$this->r07_codigo."-".$this->r07_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r07_anousu=null,$r07_mesusu=null,$r07_codigo=null,$r07_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r07_anousu,$r07_mesusu,$r07_codigo,$r07_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4130,'$r07_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4131,'$r07_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4132,'$r07_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,7473,'$r07_instit','E')");
         $resac = db_query("insert into db_acount values($acount,571,4130,'','".AddSlashes(pg_result($resaco,$iresaco,'r07_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,571,4131,'','".AddSlashes(pg_result($resaco,$iresaco,'r07_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,571,4132,'','".AddSlashes(pg_result($resaco,$iresaco,'r07_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,571,4133,'','".AddSlashes(pg_result($resaco,$iresaco,'r07_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,571,4134,'','".AddSlashes(pg_result($resaco,$iresaco,'r07_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,571,7473,'','".AddSlashes(pg_result($resaco,$iresaco,'r07_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pesdiver
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r07_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r07_anousu = $r07_anousu ";
        }
        if($r07_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r07_mesusu = $r07_mesusu ";
        }
        if($r07_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r07_codigo = '$r07_codigo' ";
        }
        if($r07_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r07_instit = $r07_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Diversos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r07_anousu."-".$r07_mesusu."-".$r07_codigo."-".$r07_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Diversos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r07_anousu."-".$r07_mesusu."-".$r07_codigo."-".$r07_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r07_anousu."-".$r07_mesusu."-".$r07_codigo."-".$r07_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:pesdiver";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function atualiza_incluir (){
  	 $this->incluir($this->r07_anousu,$this->r07_mesusu,$this->r07_codigo);
   }
   function sql_query ( $r07_anousu=null,$r07_mesusu=null,$r07_codigo=null,$r07_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = explode("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from pesdiver ";
     $sql .= "      inner join db_config  on  db_config.codigo = pesdiver.r07_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r07_anousu!=null ){
         $sql2 .= " where pesdiver.r07_anousu = $r07_anousu "; 
       } 
       if($r07_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pesdiver.r07_mesusu = $r07_mesusu "; 
       } 
       if($r07_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pesdiver.r07_codigo = '$r07_codigo' "; 
       } 
       if($r07_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pesdiver.r07_instit = $r07_instit "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = explode("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $r07_anousu=null,$r07_mesusu=null,$r07_codigo=null,$r07_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = explode("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from pesdiver ";
     $sql2 = "";
     if($dbwhere==""){
       if($r07_anousu!=null ){
         $sql2 .= " where pesdiver.r07_anousu = $r07_anousu "; 
       } 
       if($r07_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pesdiver.r07_mesusu = $r07_mesusu "; 
       } 
       if($r07_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pesdiver.r07_codigo = '$r07_codigo' "; 
       } 
       if($r07_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pesdiver.r07_instit = $r07_instit "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = explode("#",$ordem);
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