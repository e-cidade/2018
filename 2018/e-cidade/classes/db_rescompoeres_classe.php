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

//MODULO: educação
//CLASSE DA ENTIDADE rescompoeres
class cl_rescompoeres { 
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
   var $ed68_i_codigo = 0; 
   var $ed68_i_procresultado = 0; 
   var $ed68_i_procresultcomp = 0; 
   var $ed68_i_peso = 0; 
   var $ed68_c_minimoaprov = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed68_i_codigo = int8 = Código 
                 ed68_i_procresultado = int8 = Resultado 
                 ed68_i_procresultcomp = int4 = Resultado Componente 
                 ed68_i_peso = int4 = Peso 
                 ed68_c_minimoaprov = char(10) = Mínimo para Aprovação 
                 ";
   //funcao construtor da classe 
   function cl_rescompoeres() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rescompoeres"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]."?ed44_i_procresultado=".@$GLOBALS["HTTP_POST_VARS"]["ed44_i_procresultado"]."&ed42_c_descr=".@$GLOBALS["HTTP_POST_VARS"]["ed42_c_descr"]."&procedimento=".@$GLOBALS["HTTP_POST_VARS"]["procedimento"]."&forma=".@$GLOBALS["HTTP_POST_VARS"]["forma"]);
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
       $this->ed68_i_codigo = ($this->ed68_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed68_i_codigo"]:$this->ed68_i_codigo);
       $this->ed68_i_procresultado = ($this->ed68_i_procresultado == ""?@$GLOBALS["HTTP_POST_VARS"]["ed68_i_procresultado"]:$this->ed68_i_procresultado);
       $this->ed68_i_procresultcomp = ($this->ed68_i_procresultcomp == ""?@$GLOBALS["HTTP_POST_VARS"]["ed68_i_procresultcomp"]:$this->ed68_i_procresultcomp);
       $this->ed68_i_peso = ($this->ed68_i_peso == ""?@$GLOBALS["HTTP_POST_VARS"]["ed68_i_peso"]:$this->ed68_i_peso);
       $this->ed68_c_minimoaprov = ($this->ed68_c_minimoaprov == ""?@$GLOBALS["HTTP_POST_VARS"]["ed68_c_minimoaprov"]:$this->ed68_c_minimoaprov);
     }else{
       $this->ed68_i_codigo = ($this->ed68_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed68_i_codigo"]:$this->ed68_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed68_i_codigo){ 
      $this->atualizacampos();
     if($this->ed68_i_procresultado == null ){ 
       $this->erro_sql = " Campo Resultado nao Informado.";
       $this->erro_campo = "ed68_i_procresultado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed68_i_procresultcomp == null ){ 
       $this->erro_sql = " Campo Resultado Componente nao Informado.";
       $this->erro_campo = "ed68_i_procresultcomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed68_i_peso == null ){ 
       $this->erro_sql = " Campo Peso nao Informado.";
       $this->erro_campo = "ed68_i_peso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed68_i_codigo == "" || $ed68_i_codigo == null ){
       $result = db_query("select nextval('rescompoeres_ed68_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rescompoeres_ed68_i_codigo_seq do campo: ed68_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed68_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rescompoeres_ed68_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed68_i_codigo)){
         $this->erro_sql = " Campo ed68_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed68_i_codigo = $ed68_i_codigo; 
       }
     }
     if(($this->ed68_i_codigo == null) || ($this->ed68_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed68_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rescompoeres(
                                       ed68_i_codigo 
                                      ,ed68_i_procresultado 
                                      ,ed68_i_procresultcomp 
                                      ,ed68_i_peso 
                                      ,ed68_c_minimoaprov 
                       )
                values (
                                $this->ed68_i_codigo 
                               ,$this->ed68_i_procresultado 
                               ,$this->ed68_i_procresultcomp 
                               ,$this->ed68_i_peso 
                               ,'$this->ed68_c_minimoaprov' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Resultados que compoem o Resultado ($this->ed68_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Resultados que compoem o Resultado já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Resultados que compoem o Resultado ($this->ed68_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed68_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed68_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008475,'$this->ed68_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010081,1008475,'','".AddSlashes(pg_result($resaco,0,'ed68_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010081,1008476,'','".AddSlashes(pg_result($resaco,0,'ed68_i_procresultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010081,1008477,'','".AddSlashes(pg_result($resaco,0,'ed68_i_procresultcomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010081,1008478,'','".AddSlashes(pg_result($resaco,0,'ed68_i_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010081,1008479,'','".AddSlashes(pg_result($resaco,0,'ed68_c_minimoaprov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed68_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update rescompoeres set ";
     $virgula = "";
     if(trim($this->ed68_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed68_i_codigo"])){ 
       $sql  .= $virgula." ed68_i_codigo = $this->ed68_i_codigo ";
       $virgula = ",";
       if(trim($this->ed68_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed68_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed68_i_procresultado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed68_i_procresultado"])){ 
       $sql  .= $virgula." ed68_i_procresultado = $this->ed68_i_procresultado ";
       $virgula = ",";
       if(trim($this->ed68_i_procresultado) == null ){ 
         $this->erro_sql = " Campo Resultado nao Informado.";
         $this->erro_campo = "ed68_i_procresultado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed68_i_procresultcomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed68_i_procresultcomp"])){ 
       $sql  .= $virgula." ed68_i_procresultcomp = $this->ed68_i_procresultcomp ";
       $virgula = ",";
       if(trim($this->ed68_i_procresultcomp) == null ){ 
         $this->erro_sql = " Campo Resultado Componente nao Informado.";
         $this->erro_campo = "ed68_i_procresultcomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed68_i_peso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed68_i_peso"])){ 
       $sql  .= $virgula." ed68_i_peso = $this->ed68_i_peso ";
       $virgula = ",";
       if(trim($this->ed68_i_peso) == null ){ 
         $this->erro_sql = " Campo Peso nao Informado.";
         $this->erro_campo = "ed68_i_peso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed68_c_minimoaprov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed68_c_minimoaprov"])){ 
       $sql  .= $virgula." ed68_c_minimoaprov = '$this->ed68_c_minimoaprov' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed68_i_codigo!=null){
       $sql .= " ed68_i_codigo = $this->ed68_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed68_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008475,'$this->ed68_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed68_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010081,1008475,'".AddSlashes(pg_result($resaco,$conresaco,'ed68_i_codigo'))."','$this->ed68_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed68_i_procresultado"]))
           $resac = db_query("insert into db_acount values($acount,1010081,1008476,'".AddSlashes(pg_result($resaco,$conresaco,'ed68_i_procresultado'))."','$this->ed68_i_procresultado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed68_i_procresultcomp"]))
           $resac = db_query("insert into db_acount values($acount,1010081,1008477,'".AddSlashes(pg_result($resaco,$conresaco,'ed68_i_procresultcomp'))."','$this->ed68_i_procresultcomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed68_i_peso"]))
           $resac = db_query("insert into db_acount values($acount,1010081,1008478,'".AddSlashes(pg_result($resaco,$conresaco,'ed68_i_peso'))."','$this->ed68_i_peso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed68_c_minimoaprov"]))
           $resac = db_query("insert into db_acount values($acount,1010081,1008479,'".AddSlashes(pg_result($resaco,$conresaco,'ed68_c_minimoaprov'))."','$this->ed68_c_minimoaprov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultados que compoem o Resultado nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed68_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Resultados que compoem o Resultado nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed68_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed68_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed68_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed68_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008475,'$ed68_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010081,1008475,'','".AddSlashes(pg_result($resaco,$iresaco,'ed68_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010081,1008476,'','".AddSlashes(pg_result($resaco,$iresaco,'ed68_i_procresultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010081,1008477,'','".AddSlashes(pg_result($resaco,$iresaco,'ed68_i_procresultcomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010081,1008478,'','".AddSlashes(pg_result($resaco,$iresaco,'ed68_i_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010081,1008479,'','".AddSlashes(pg_result($resaco,$iresaco,'ed68_c_minimoaprov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rescompoeres
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed68_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed68_i_codigo = $ed68_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultados que compoem o Resultado nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed68_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Resultados que compoem o Resultado nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed68_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed68_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:rescompoeres";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed68_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rescompoeres ";
     $sql .= "      inner join procresultado as procres on res.ed43_i_codigo = rescompoeres.ed68_i_procresultado";
     $sql .= "      inner join procresultado as procrescomp on rescomp.ed43_i_codigo = rescompoeres.ed68_i_procresultcomp";
     $sql .= "      inner join formaavaliacao on formaavaliacao.ed37_i_codigo = procresultado.ed43_i_formaavaliacao";
     $sql .= "      inner join procedimento on procedimento.ed40_i_codigo = procresultado.ed43_i_procedimento";
     $sql .= "      inner join resultado as res on res.ed42_i_codigo = procresultado.ed43_i_resultado";
     $sql .= "      inner join resultado as rescomp on rescomp.ed42_i_codigo = procresultado.ed43_i_resultado";
     $sql2 = "";
     if($dbwhere==""){
       if($ed68_i_codigo!=null ){
         $sql2 .= " where rescompoeres.ed68_i_codigo = $ed68_i_codigo "; 
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
   function sql_query_file ( $ed68_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rescompoeres ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed68_i_codigo!=null ){
         $sql2 .= " where rescompoeres.ed68_i_codigo = $ed68_i_codigo "; 
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