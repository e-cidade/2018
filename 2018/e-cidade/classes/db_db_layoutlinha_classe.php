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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_layoutlinha
class cl_db_layoutlinha { 
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
   var $db51_codigo = 0; 
   var $db51_layouttxt = 0; 
   var $db51_descr = null; 
   var $db51_tipolinha = 0; 
   var $db51_tamlinha = 0; 
   var $db51_linhasantes = 0; 
   var $db51_linhasdepois = 0; 
   var $db51_obs = null; 
   var $db51_separador = null; 
   var $db51_compacta = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db51_codigo = int4 = Código da linha 
                 db51_layouttxt = int4 = Código do layout 
                 db51_descr = varchar(40) = Descrição 
                 db51_tipolinha = int4 = Tipo de linha 
                 db51_tamlinha = int4 = Tamanho da linha 
                 db51_linhasantes = int4 = Quebras antes 
                 db51_linhasdepois = int4 = Quebras depois 
                 db51_obs = text = Observações 
                 db51_separador = varchar(10) = Separador 
                 db51_compacta = bool = Compacta 
                 ";
   //funcao construtor da classe 
   function cl_db_layoutlinha() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_layoutlinha"); 
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
       $this->db51_codigo = ($this->db51_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db51_codigo"]:$this->db51_codigo);
       $this->db51_layouttxt = ($this->db51_layouttxt == ""?@$GLOBALS["HTTP_POST_VARS"]["db51_layouttxt"]:$this->db51_layouttxt);
       $this->db51_descr = ($this->db51_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["db51_descr"]:$this->db51_descr);
       $this->db51_tipolinha = ($this->db51_tipolinha == ""?@$GLOBALS["HTTP_POST_VARS"]["db51_tipolinha"]:$this->db51_tipolinha);
       $this->db51_tamlinha = ($this->db51_tamlinha == ""?@$GLOBALS["HTTP_POST_VARS"]["db51_tamlinha"]:$this->db51_tamlinha);
       $this->db51_linhasantes = ($this->db51_linhasantes == ""?@$GLOBALS["HTTP_POST_VARS"]["db51_linhasantes"]:$this->db51_linhasantes);
       $this->db51_linhasdepois = ($this->db51_linhasdepois == ""?@$GLOBALS["HTTP_POST_VARS"]["db51_linhasdepois"]:$this->db51_linhasdepois);
       $this->db51_obs = ($this->db51_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["db51_obs"]:$this->db51_obs);
       $this->db51_separador = ($this->db51_separador == ""?@$GLOBALS["HTTP_POST_VARS"]["db51_separador"]:$this->db51_separador);
       $this->db51_compacta = ($this->db51_compacta == "f"?@$GLOBALS["HTTP_POST_VARS"]["db51_compacta"]:$this->db51_compacta);
     }else{
       $this->db51_codigo = ($this->db51_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db51_codigo"]:$this->db51_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($db51_codigo){ 
      $this->atualizacampos();
     if($this->db51_layouttxt == null ){ 
       $this->erro_sql = " Campo Código do layout nao Informado.";
       $this->erro_campo = "db51_layouttxt";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db51_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "db51_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db51_tipolinha == null ){ 
       $this->erro_sql = " Campo Tipo de linha nao Informado.";
       $this->erro_campo = "db51_tipolinha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db51_tamlinha == null ){ 
       $this->erro_sql = " Campo Tamanho da linha nao Informado.";
       $this->erro_campo = "db51_tamlinha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db51_linhasantes == null ){ 
       $this->db51_linhasantes = "0";
     }
     if($this->db51_linhasdepois == null ){ 
       $this->db51_linhasdepois = "0";
     }
     if($this->db51_compacta == null ){ 
       $this->erro_sql = " Campo Compacta nao Informado.";
       $this->erro_campo = "db51_compacta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db51_codigo == "" || $db51_codigo == null ){
       $result = db_query("select nextval('db_layoutlinha_db51_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_layoutlinha_db51_codigo_seq do campo: db51_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db51_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_layoutlinha_db51_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $db51_codigo)){
         $this->erro_sql = " Campo db51_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db51_codigo = $db51_codigo; 
       }
     }
     if(($this->db51_codigo == null) || ($this->db51_codigo == "") ){ 
       $this->erro_sql = " Campo db51_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_layoutlinha(
                                       db51_codigo 
                                      ,db51_layouttxt 
                                      ,db51_descr 
                                      ,db51_tipolinha 
                                      ,db51_tamlinha 
                                      ,db51_linhasantes 
                                      ,db51_linhasdepois 
                                      ,db51_obs 
                                      ,db51_separador 
                                      ,db51_compacta 
                       )
                values (
                                $this->db51_codigo 
                               ,$this->db51_layouttxt 
                               ,'$this->db51_descr' 
                               ,$this->db51_tipolinha 
                               ,$this->db51_tamlinha 
                               ,$this->db51_linhasantes 
                               ,$this->db51_linhasdepois 
                               ,'$this->db51_obs' 
                               ,'$this->db51_separador' 
                               ,'$this->db51_compacta' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de linhas do layout ($this->db51_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de linhas do layout já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de linhas do layout ($this->db51_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db51_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db51_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9069,'$this->db51_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1554,9069,'','".AddSlashes(pg_result($resaco,0,'db51_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1554,9070,'','".AddSlashes(pg_result($resaco,0,'db51_layouttxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1554,9087,'','".AddSlashes(pg_result($resaco,0,'db51_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1554,9072,'','".AddSlashes(pg_result($resaco,0,'db51_tipolinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1554,9068,'','".AddSlashes(pg_result($resaco,0,'db51_tamlinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1554,9101,'','".AddSlashes(pg_result($resaco,0,'db51_linhasantes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1554,9106,'','".AddSlashes(pg_result($resaco,0,'db51_linhasdepois'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1554,9098,'','".AddSlashes(pg_result($resaco,0,'db51_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1554,13342,'','".AddSlashes(pg_result($resaco,0,'db51_separador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1554,13343,'','".AddSlashes(pg_result($resaco,0,'db51_compacta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db51_codigo=null) { 
      $this->atualizacampos();
     $sql = " update db_layoutlinha set ";
     $virgula = "";
     if(trim($this->db51_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db51_codigo"])){ 
       $sql  .= $virgula." db51_codigo = $this->db51_codigo ";
       $virgula = ",";
       if(trim($this->db51_codigo) == null ){ 
         $this->erro_sql = " Campo Código da linha nao Informado.";
         $this->erro_campo = "db51_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db51_layouttxt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db51_layouttxt"])){ 
       $sql  .= $virgula." db51_layouttxt = $this->db51_layouttxt ";
       $virgula = ",";
       if(trim($this->db51_layouttxt) == null ){ 
         $this->erro_sql = " Campo Código do layout nao Informado.";
         $this->erro_campo = "db51_layouttxt";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db51_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db51_descr"])){ 
       $sql  .= $virgula." db51_descr = '$this->db51_descr' ";
       $virgula = ",";
       if(trim($this->db51_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "db51_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db51_tipolinha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db51_tipolinha"])){ 
       $sql  .= $virgula." db51_tipolinha = $this->db51_tipolinha ";
       $virgula = ",";
       if(trim($this->db51_tipolinha) == null ){ 
         $this->erro_sql = " Campo Tipo de linha nao Informado.";
         $this->erro_campo = "db51_tipolinha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db51_tamlinha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db51_tamlinha"])){ 
       $sql  .= $virgula." db51_tamlinha = $this->db51_tamlinha ";
       $virgula = ",";
       if(trim($this->db51_tamlinha) == null ){ 
         $this->erro_sql = " Campo Tamanho da linha nao Informado.";
         $this->erro_campo = "db51_tamlinha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db51_linhasantes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db51_linhasantes"])){ 
        if(trim($this->db51_linhasantes)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db51_linhasantes"])){ 
           $this->db51_linhasantes = "0" ; 
        } 
       $sql  .= $virgula." db51_linhasantes = $this->db51_linhasantes ";
       $virgula = ",";
     }
     if(trim($this->db51_linhasdepois)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db51_linhasdepois"])){ 
        if(trim($this->db51_linhasdepois)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db51_linhasdepois"])){ 
           $this->db51_linhasdepois = "0" ; 
        } 
       $sql  .= $virgula." db51_linhasdepois = $this->db51_linhasdepois ";
       $virgula = ",";
     }
     if(trim($this->db51_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db51_obs"])){ 
       $sql  .= $virgula." db51_obs = '$this->db51_obs' ";
       $virgula = ",";
     }
     if(trim($this->db51_separador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db51_separador"])){ 
       $sql  .= $virgula." db51_separador = '$this->db51_separador' ";
       $virgula = ",";
     }
     if(trim($this->db51_compacta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db51_compacta"])){ 
       $sql  .= $virgula." db51_compacta = '$this->db51_compacta' ";
       $virgula = ",";
       if(trim($this->db51_compacta) == null ){ 
         $this->erro_sql = " Campo Compacta nao Informado.";
         $this->erro_campo = "db51_compacta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db51_codigo!=null){
       $sql .= " db51_codigo = $this->db51_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db51_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9069,'$this->db51_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db51_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1554,9069,'".AddSlashes(pg_result($resaco,$conresaco,'db51_codigo'))."','$this->db51_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db51_layouttxt"]))
           $resac = db_query("insert into db_acount values($acount,1554,9070,'".AddSlashes(pg_result($resaco,$conresaco,'db51_layouttxt'))."','$this->db51_layouttxt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db51_descr"]))
           $resac = db_query("insert into db_acount values($acount,1554,9087,'".AddSlashes(pg_result($resaco,$conresaco,'db51_descr'))."','$this->db51_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db51_tipolinha"]))
           $resac = db_query("insert into db_acount values($acount,1554,9072,'".AddSlashes(pg_result($resaco,$conresaco,'db51_tipolinha'))."','$this->db51_tipolinha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db51_tamlinha"]))
           $resac = db_query("insert into db_acount values($acount,1554,9068,'".AddSlashes(pg_result($resaco,$conresaco,'db51_tamlinha'))."','$this->db51_tamlinha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db51_linhasantes"]))
           $resac = db_query("insert into db_acount values($acount,1554,9101,'".AddSlashes(pg_result($resaco,$conresaco,'db51_linhasantes'))."','$this->db51_linhasantes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db51_linhasdepois"]))
           $resac = db_query("insert into db_acount values($acount,1554,9106,'".AddSlashes(pg_result($resaco,$conresaco,'db51_linhasdepois'))."','$this->db51_linhasdepois',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db51_obs"]))
           $resac = db_query("insert into db_acount values($acount,1554,9098,'".AddSlashes(pg_result($resaco,$conresaco,'db51_obs'))."','$this->db51_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db51_separador"]))
           $resac = db_query("insert into db_acount values($acount,1554,13342,'".AddSlashes(pg_result($resaco,$conresaco,'db51_separador'))."','$this->db51_separador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db51_compacta"]))
           $resac = db_query("insert into db_acount values($acount,1554,13343,'".AddSlashes(pg_result($resaco,$conresaco,'db51_compacta'))."','$this->db51_compacta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de linhas do layout nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db51_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de linhas do layout nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db51_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db51_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db51_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db51_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9069,'$db51_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1554,9069,'','".AddSlashes(pg_result($resaco,$iresaco,'db51_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1554,9070,'','".AddSlashes(pg_result($resaco,$iresaco,'db51_layouttxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1554,9087,'','".AddSlashes(pg_result($resaco,$iresaco,'db51_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1554,9072,'','".AddSlashes(pg_result($resaco,$iresaco,'db51_tipolinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1554,9068,'','".AddSlashes(pg_result($resaco,$iresaco,'db51_tamlinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1554,9101,'','".AddSlashes(pg_result($resaco,$iresaco,'db51_linhasantes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1554,9106,'','".AddSlashes(pg_result($resaco,$iresaco,'db51_linhasdepois'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1554,9098,'','".AddSlashes(pg_result($resaco,$iresaco,'db51_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1554,13342,'','".AddSlashes(pg_result($resaco,$iresaco,'db51_separador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1554,13343,'','".AddSlashes(pg_result($resaco,$iresaco,'db51_compacta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_layoutlinha
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db51_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db51_codigo = $db51_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de linhas do layout nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db51_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de linhas do layout nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db51_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db51_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_layoutlinha";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db51_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_layoutlinha ";
     $sql .= "      inner join db_layouttxt  on  db_layouttxt.db50_codigo = db_layoutlinha.db51_layouttxt";
     $sql2 = "";
     if($dbwhere==""){
       if($db51_codigo!=null ){
         $sql2 .= " where db_layoutlinha.db51_codigo = $db51_codigo "; 
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
   function sql_query_file ( $db51_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_layoutlinha ";
     $sql2 = "";
     if($dbwhere==""){
       if($db51_codigo!=null ){
         $sql2 .= " where db_layoutlinha.db51_codigo = $db51_codigo "; 
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