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
//CLASSE DA ENTIDADE avalcompoeres
class cl_avalcompoeres { 
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
   var $ed44_i_codigo = 0; 
   var $ed44_i_procavaliacao = 0; 
   var $ed44_i_procresultado = 0; 
   var $ed44_i_peso = 0; 
   var $ed44_c_obrigatorio = null; 
   var $ed44_c_minimoaprov = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed44_i_codigo = int8 = Código 
                 ed44_i_procavaliacao = int8 = Período de Avaliação 
                 ed44_i_procresultado = int8 = Resultado 
                 ed44_i_peso = int4 = Peso 
                 ed44_c_obrigatorio = char(1) = Obrigatório 
                 ed44_c_minimoaprov = char(10) = Mínimo 
                 ";
   //funcao construtor da classe 
   function cl_avalcompoeres() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avalcompoeres");
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
       $this->ed44_i_codigo = ($this->ed44_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed44_i_codigo"]:$this->ed44_i_codigo);
       $this->ed44_i_procavaliacao = ($this->ed44_i_procavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed44_i_procavaliacao"]:$this->ed44_i_procavaliacao);
       $this->ed44_i_procresultado = ($this->ed44_i_procresultado == ""?@$GLOBALS["HTTP_POST_VARS"]["ed44_i_procresultado"]:$this->ed44_i_procresultado);
       $this->ed44_i_peso = ($this->ed44_i_peso == ""?@$GLOBALS["HTTP_POST_VARS"]["ed44_i_peso"]:$this->ed44_i_peso);
       $this->ed44_c_obrigatorio = ($this->ed44_c_obrigatorio == ""?@$GLOBALS["HTTP_POST_VARS"]["ed44_c_obrigatorio"]:$this->ed44_c_obrigatorio);
       $this->ed44_c_minimoaprov = ($this->ed44_c_minimoaprov == ""?@$GLOBALS["HTTP_POST_VARS"]["ed44_c_minimoaprov"]:$this->ed44_c_minimoaprov);
     }else{
       $this->ed44_i_codigo = ($this->ed44_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed44_i_codigo"]:$this->ed44_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed44_i_codigo){ 
      $this->atualizacampos();
     if($this->ed44_i_procavaliacao == null ){ 
       $this->erro_sql = " Campo Período de Avaliação nao Informado.";
       $this->erro_campo = "ed44_i_procavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed44_i_procresultado == null ){ 
       $this->erro_sql = " Campo Resultado nao Informado.";
       $this->erro_campo = "ed44_i_procresultado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed44_i_peso == null ){ 
       $this->erro_sql = " Campo Peso nao Informado.";
       $this->erro_campo = "ed44_i_peso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed44_c_obrigatorio == null ){ 
       $this->erro_sql = " Campo Obrigatório nao Informado.";
       $this->erro_campo = "ed44_c_obrigatorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed44_i_codigo == "" || $ed44_i_codigo == null ){
       $result = db_query("select nextval('avalcompoeresult_ed44_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avalcompoeresult_ed44_i_codigo_seq do campo: ed44_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed44_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avalcompoeresult_ed44_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed44_i_codigo)){
         $this->erro_sql = " Campo ed44_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed44_i_codigo = $ed44_i_codigo; 
       }
     }
     if(($this->ed44_i_codigo == null) || ($this->ed44_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed44_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avalcompoeres(
                                       ed44_i_codigo 
                                      ,ed44_i_procavaliacao 
                                      ,ed44_i_procresultado 
                                      ,ed44_i_peso 
                                      ,ed44_c_obrigatorio 
                                      ,ed44_c_minimoaprov 
                       )
                values (
                                $this->ed44_i_codigo 
                               ,$this->ed44_i_procavaliacao 
                               ,$this->ed44_i_procresultado 
                               ,$this->ed44_i_peso 
                               ,'$this->ed44_c_obrigatorio' 
                               ,'$this->ed44_c_minimoaprov' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avaliações que compoem o Resultado ($this->ed44_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avaliações que compoem o Resultado já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avaliações que compoem o Resultado ($this->ed44_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed44_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed44_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008469,'$this->ed44_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010080,1008469,'','".AddSlashes(pg_result($resaco,0,'ed44_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010080,1008470,'','".AddSlashes(pg_result($resaco,0,'ed44_i_procavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010080,1008471,'','".AddSlashes(pg_result($resaco,0,'ed44_i_procresultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010080,1008472,'','".AddSlashes(pg_result($resaco,0,'ed44_i_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010080,1008473,'','".AddSlashes(pg_result($resaco,0,'ed44_c_obrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010080,1008474,'','".AddSlashes(pg_result($resaco,0,'ed44_c_minimoaprov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed44_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update avalcompoeres set ";
     $virgula = "";
     if(trim($this->ed44_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed44_i_codigo"])){ 
       $sql  .= $virgula." ed44_i_codigo = $this->ed44_i_codigo ";
       $virgula = ",";
       if(trim($this->ed44_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed44_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed44_i_procavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed44_i_procavaliacao"])){ 
       $sql  .= $virgula." ed44_i_procavaliacao = $this->ed44_i_procavaliacao ";
       $virgula = ",";
       if(trim($this->ed44_i_procavaliacao) == null ){ 
         $this->erro_sql = " Campo Período de Avaliação nao Informado.";
         $this->erro_campo = "ed44_i_procavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed44_i_procresultado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed44_i_procresultado"])){ 
       $sql  .= $virgula." ed44_i_procresultado = $this->ed44_i_procresultado ";
       $virgula = ",";
       if(trim($this->ed44_i_procresultado) == null ){ 
         $this->erro_sql = " Campo Resultado nao Informado.";
         $this->erro_campo = "ed44_i_procresultado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed44_i_peso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed44_i_peso"])){ 
       $sql  .= $virgula." ed44_i_peso = $this->ed44_i_peso ";
       $virgula = ",";
       if(trim($this->ed44_i_peso) == null ){ 
         $this->erro_sql = " Campo Peso nao Informado.";
         $this->erro_campo = "ed44_i_peso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed44_c_obrigatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed44_c_obrigatorio"])){ 
       $sql  .= $virgula." ed44_c_obrigatorio = '$this->ed44_c_obrigatorio' ";
       $virgula = ",";
       if(trim($this->ed44_c_obrigatorio) == null ){ 
         $this->erro_sql = " Campo Obrigatório nao Informado.";
         $this->erro_campo = "ed44_c_obrigatorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed44_c_minimoaprov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed44_c_minimoaprov"])){ 
       $sql  .= $virgula." ed44_c_minimoaprov = '$this->ed44_c_minimoaprov' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed44_i_codigo!=null){
       $sql .= " ed44_i_codigo = $this->ed44_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed44_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008469,'$this->ed44_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed44_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010080,1008469,'".AddSlashes(pg_result($resaco,$conresaco,'ed44_i_codigo'))."','$this->ed44_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed44_i_procavaliacao"]))
           $resac = db_query("insert into db_acount values($acount,1010080,1008470,'".AddSlashes(pg_result($resaco,$conresaco,'ed44_i_procavaliacao'))."','$this->ed44_i_procavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed44_i_procresultado"]))
           $resac = db_query("insert into db_acount values($acount,1010080,1008471,'".AddSlashes(pg_result($resaco,$conresaco,'ed44_i_procresultado'))."','$this->ed44_i_procresultado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed44_i_peso"]))
           $resac = db_query("insert into db_acount values($acount,1010080,1008472,'".AddSlashes(pg_result($resaco,$conresaco,'ed44_i_peso'))."','$this->ed44_i_peso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed44_c_obrigatorio"]))
           $resac = db_query("insert into db_acount values($acount,1010080,1008473,'".AddSlashes(pg_result($resaco,$conresaco,'ed44_c_obrigatorio'))."','$this->ed44_c_obrigatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed44_c_minimoaprov"]))
           $resac = db_query("insert into db_acount values($acount,1010080,1008474,'".AddSlashes(pg_result($resaco,$conresaco,'ed44_c_minimoaprov'))."','$this->ed44_c_minimoaprov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliações que compoem o Resultado nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed44_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliações que compoem o Resultado nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed44_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed44_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed44_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed44_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008469,'$ed44_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010080,1008469,'','".AddSlashes(pg_result($resaco,$iresaco,'ed44_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010080,1008470,'','".AddSlashes(pg_result($resaco,$iresaco,'ed44_i_procavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010080,1008471,'','".AddSlashes(pg_result($resaco,$iresaco,'ed44_i_procresultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010080,1008472,'','".AddSlashes(pg_result($resaco,$iresaco,'ed44_i_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010080,1008473,'','".AddSlashes(pg_result($resaco,$iresaco,'ed44_c_obrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010080,1008474,'','".AddSlashes(pg_result($resaco,$iresaco,'ed44_c_minimoaprov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from avalcompoeres
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed44_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed44_i_codigo = $ed44_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliações que compoem o Resultado nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed44_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliações que compoem o Resultado nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed44_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed44_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:avalcompoeres";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed44_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avalcompoeres ";
     $sql .= "      inner join procavaliacao  on  procavaliacao.ed41_i_codigo = avalcompoeres.ed44_i_procavaliacao";
     $sql .= "      inner join procresultado  on  procresultado.ed43_i_codigo = avalcompoeres.ed44_i_procresultado";
     $sql .= "      inner join periodoavaliacao  on  periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao";
     $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = procavaliacao.ed41_i_formaavaliacao";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = procavaliacao.ed41_i_procedimento";
     $sql .= "      inner join formaavaliacao  as a on   a.ed37_i_codigo = procresultado.ed43_i_formaavaliacao";
     $sql .= "      inner join procedimento  as b on   b.ed40_i_codigo = procresultado.ed43_i_procedimento";
     $sql .= "      inner join resultado  on  resultado.ed42_i_codigo = procresultado.ed43_i_resultado";
     $sql2 = "";
     if($dbwhere==""){
       if($ed44_i_codigo!=null ){
         $sql2 .= " where avalcompoeres.ed44_i_codigo = $ed44_i_codigo "; 
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
   function sql_query_file ( $ed44_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avalcompoeres ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed44_i_codigo!=null ){
         $sql2 .= " where avalcompoeres.ed44_i_codigo = $ed44_i_codigo "; 
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