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
//CLASSE DA ENTIDADE rhlota
class cl_rhlota { 
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
   var $r70_codigo = 0; 
   var $r70_codestrut = 0; 
   var $r70_estrut = null; 
   var $r70_descr = null; 
   var $r70_analitica = 'f'; 
   var $r70_instit = 0; 
   var $r70_ativo = 'f'; 
   var $r70_numcgm = 0; 
   var $r70_concarpeculiar = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r70_codigo = int4 = Código da Lotação 
                 r70_codestrut = int4 = Código da estrutura 
                 r70_estrut = varchar(20) = Estrutural da Lotação 
                 r70_descr = varchar(50) = Descrição 
                 r70_analitica = bool = Analitica 
                 r70_instit = int4 = codigo da instituicao 
                 r70_ativo = bool = Ativo 
                 r70_numcgm = int4 = Numcgm 
                 r70_concarpeculiar = varchar(100) = Caracteristica Peculiar 
                 ";
   //funcao construtor da classe 
   function cl_rhlota() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhlota"); 
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
       $this->r70_codigo = ($this->r70_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r70_codigo"]:$this->r70_codigo);
       $this->r70_codestrut = ($this->r70_codestrut == ""?@$GLOBALS["HTTP_POST_VARS"]["r70_codestrut"]:$this->r70_codestrut);
       $this->r70_estrut = ($this->r70_estrut == ""?@$GLOBALS["HTTP_POST_VARS"]["r70_estrut"]:$this->r70_estrut);
       $this->r70_descr = ($this->r70_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["r70_descr"]:$this->r70_descr);
       $this->r70_analitica = ($this->r70_analitica == "f"?@$GLOBALS["HTTP_POST_VARS"]["r70_analitica"]:$this->r70_analitica);
       $this->r70_instit = ($this->r70_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r70_instit"]:$this->r70_instit);
       $this->r70_ativo = ($this->r70_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["r70_ativo"]:$this->r70_ativo);
       $this->r70_numcgm = ($this->r70_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["r70_numcgm"]:$this->r70_numcgm);
       $this->r70_concarpeculiar = ($this->r70_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["r70_concarpeculiar"]:$this->r70_concarpeculiar);
     }else{
       $this->r70_codigo = ($this->r70_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r70_codigo"]:$this->r70_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($r70_codigo){ 
      $this->atualizacampos();
     if($this->r70_codestrut == null ){ 
       $this->erro_sql = " Campo Código da estrutura nao Informado.";
       $this->erro_campo = "r70_codestrut";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r70_estrut == null ){ 
       $this->erro_sql = " Campo Estrutural da Lotação nao Informado.";
       $this->erro_campo = "r70_estrut";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r70_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "r70_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r70_analitica == null ){ 
       $this->erro_sql = " Campo Analitica nao Informado.";
       $this->erro_campo = "r70_analitica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r70_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "r70_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r70_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "r70_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r70_numcgm == null ){ 
       $this->erro_sql = " Campo Numcgm nao Informado.";
       $this->erro_campo = "r70_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r70_concarpeculiar == null ){ 
       $this->erro_sql = " Campo Caracteristica Peculiar nao Informado.";
       $this->erro_campo = "r70_concarpeculiar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($r70_codigo == "" || $r70_codigo == null ){
       $result = db_query("select nextval('rhlota_r70_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhlota_r70_codigo_seq do campo: r70_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->r70_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhlota_r70_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $r70_codigo)){
         $this->erro_sql = " Campo r70_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->r70_codigo = $r70_codigo; 
       }
     }
     if(($this->r70_codigo == null) || ($this->r70_codigo == "") ){ 
       $this->erro_sql = " Campo r70_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhlota(
                                       r70_codigo 
                                      ,r70_codestrut 
                                      ,r70_estrut 
                                      ,r70_descr 
                                      ,r70_analitica 
                                      ,r70_instit 
                                      ,r70_ativo 
                                      ,r70_numcgm 
                                      ,r70_concarpeculiar 
                       )
                values (
                                $this->r70_codigo 
                               ,$this->r70_codestrut 
                               ,'$this->r70_estrut' 
                               ,'$this->r70_descr' 
                               ,'$this->r70_analitica' 
                               ,$this->r70_instit 
                               ,'$this->r70_ativo' 
                               ,$this->r70_numcgm 
                               ,'$this->r70_concarpeculiar' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro das Lotações ($this->r70_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro das Lotações já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro das Lotações ($this->r70_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r70_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r70_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5678,'$this->r70_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,894,5678,'','".AddSlashes(pg_result($resaco,0,'r70_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,894,5701,'','".AddSlashes(pg_result($resaco,0,'r70_codestrut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,894,5680,'','".AddSlashes(pg_result($resaco,0,'r70_estrut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,894,5681,'','".AddSlashes(pg_result($resaco,0,'r70_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,894,5704,'','".AddSlashes(pg_result($resaco,0,'r70_analitica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,894,7470,'','".AddSlashes(pg_result($resaco,0,'r70_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,894,11885,'','".AddSlashes(pg_result($resaco,0,'r70_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,894,12662,'','".AddSlashes(pg_result($resaco,0,'r70_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,894,15050,'','".AddSlashes(pg_result($resaco,0,'r70_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r70_codigo=null) { 
      $this->atualizacampos();
     $sql = " update rhlota set ";
     $virgula = "";
     if(trim($this->r70_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r70_codigo"])){ 
       $sql  .= $virgula." r70_codigo = $this->r70_codigo ";
       $virgula = ",";
       if(trim($this->r70_codigo) == null ){ 
         $this->erro_sql = " Campo Código da Lotação nao Informado.";
         $this->erro_campo = "r70_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r70_codestrut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r70_codestrut"])){ 
       $sql  .= $virgula." r70_codestrut = $this->r70_codestrut ";
       $virgula = ",";
       if(trim($this->r70_codestrut) == null ){ 
         $this->erro_sql = " Campo Código da estrutura nao Informado.";
         $this->erro_campo = "r70_codestrut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r70_estrut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r70_estrut"])){ 
       $sql  .= $virgula." r70_estrut = '$this->r70_estrut' ";
       $virgula = ",";
       if(trim($this->r70_estrut) == null ){ 
         $this->erro_sql = " Campo Estrutural da Lotação nao Informado.";
         $this->erro_campo = "r70_estrut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r70_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r70_descr"])){ 
       $sql  .= $virgula." r70_descr = '$this->r70_descr' ";
       $virgula = ",";
       if(trim($this->r70_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "r70_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r70_analitica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r70_analitica"])){ 
       $sql  .= $virgula." r70_analitica = '$this->r70_analitica' ";
       $virgula = ",";
       if(trim($this->r70_analitica) == null ){ 
         $this->erro_sql = " Campo Analitica nao Informado.";
         $this->erro_campo = "r70_analitica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r70_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r70_instit"])){ 
       $sql  .= $virgula." r70_instit = $this->r70_instit ";
       $virgula = ",";
       if(trim($this->r70_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "r70_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r70_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r70_ativo"])){ 
       $sql  .= $virgula." r70_ativo = '$this->r70_ativo' ";
       $virgula = ",";
       if(trim($this->r70_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "r70_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r70_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r70_numcgm"])){ 
       $sql  .= $virgula." r70_numcgm = $this->r70_numcgm ";
       $virgula = ",";
       if(trim($this->r70_numcgm) == null ){ 
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "r70_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r70_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r70_concarpeculiar"])){ 
       $sql  .= $virgula." r70_concarpeculiar = '$this->r70_concarpeculiar' ";
       $virgula = ",";
       if(trim($this->r70_concarpeculiar) == null ){ 
         $this->erro_sql = " Campo Caracteristica Peculiar nao Informado.";
         $this->erro_campo = "r70_concarpeculiar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r70_codigo!=null){
       $sql .= " r70_codigo = $this->r70_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r70_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5678,'$this->r70_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r70_codigo"]) || $this->r70_codigo != "")
           $resac = db_query("insert into db_acount values($acount,894,5678,'".AddSlashes(pg_result($resaco,$conresaco,'r70_codigo'))."','$this->r70_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r70_codestrut"]) || $this->r70_codestrut != "")
           $resac = db_query("insert into db_acount values($acount,894,5701,'".AddSlashes(pg_result($resaco,$conresaco,'r70_codestrut'))."','$this->r70_codestrut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r70_estrut"]) || $this->r70_estrut != "")
           $resac = db_query("insert into db_acount values($acount,894,5680,'".AddSlashes(pg_result($resaco,$conresaco,'r70_estrut'))."','$this->r70_estrut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r70_descr"]) || $this->r70_descr != "")
           $resac = db_query("insert into db_acount values($acount,894,5681,'".AddSlashes(pg_result($resaco,$conresaco,'r70_descr'))."','$this->r70_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r70_analitica"]) || $this->r70_analitica != "")
           $resac = db_query("insert into db_acount values($acount,894,5704,'".AddSlashes(pg_result($resaco,$conresaco,'r70_analitica'))."','$this->r70_analitica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r70_instit"]) || $this->r70_instit != "")
           $resac = db_query("insert into db_acount values($acount,894,7470,'".AddSlashes(pg_result($resaco,$conresaco,'r70_instit'))."','$this->r70_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r70_ativo"]) || $this->r70_ativo != "")
           $resac = db_query("insert into db_acount values($acount,894,11885,'".AddSlashes(pg_result($resaco,$conresaco,'r70_ativo'))."','$this->r70_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r70_numcgm"]) || $this->r70_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,894,12662,'".AddSlashes(pg_result($resaco,$conresaco,'r70_numcgm'))."','$this->r70_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r70_concarpeculiar"]) || $this->r70_concarpeculiar != "")
           $resac = db_query("insert into db_acount values($acount,894,15050,'".AddSlashes(pg_result($resaco,$conresaco,'r70_concarpeculiar'))."','$this->r70_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro das Lotações nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r70_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das Lotações nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r70_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r70_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r70_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r70_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5678,'$r70_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,894,5678,'','".AddSlashes(pg_result($resaco,$iresaco,'r70_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,894,5701,'','".AddSlashes(pg_result($resaco,$iresaco,'r70_codestrut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,894,5680,'','".AddSlashes(pg_result($resaco,$iresaco,'r70_estrut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,894,5681,'','".AddSlashes(pg_result($resaco,$iresaco,'r70_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,894,5704,'','".AddSlashes(pg_result($resaco,$iresaco,'r70_analitica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,894,7470,'','".AddSlashes(pg_result($resaco,$iresaco,'r70_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,894,11885,'','".AddSlashes(pg_result($resaco,$iresaco,'r70_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,894,12662,'','".AddSlashes(pg_result($resaco,$iresaco,'r70_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,894,15050,'','".AddSlashes(pg_result($resaco,$iresaco,'r70_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhlota
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r70_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r70_codigo = $r70_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro das Lotações nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r70_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das Lotações nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r70_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r70_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhlota";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $r70_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhlota ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhlota.r70_numcgm";
     $sql .= "      inner join db_estrutura  on  db_estrutura.db77_codestrut = rhlota.r70_codestrut";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = rhlota.r70_concarpeculiar";
     $sql2 = "";
     if($dbwhere==""){
       if($r70_codigo!=null ){
         $sql2 .= " where rhlota.r70_codigo = $r70_codigo "; 
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
   function sql_query_file ( $r70_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhlota ";
     $sql2 = "";
     if($dbwhere==""){
       if($r70_codigo!=null ){
         $sql2 .= " where rhlota.r70_codigo = $r70_codigo "; 
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
   function sql_query_cgm ( $r70_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhlota ";
     $sql .= "      inner join rhpessoal  on rhpessoal.rh01_lotac = rhlota.r70_codigo  ";
     $sql .= "      inner join rhpessoalmov  on rhpessoalmov.rh02_regist = rhpessoal.rh01_regist ";
     $sql .= "      left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
     $sql .= "      inner join rhregime  on rhregime.rh30_codreg  = rhpessoalmov.rh02_codreg ";
     $sql .= "      inner join cgm      on cgm.z01_numcgm         = rhpessoal.rh01_numcgm ";
     $sql .= "      inner join rhfuncao on rhfuncao.rh37_funcao   = rhpessoal.rh01_funcao ";
     $sql2 = "";
     if($dbwhere==""){
       if($r70_codigo!=null ){
         $sql2 .= " where rhlota.r70_codigo = $r70_codigo "; 
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
   function sql_query_leftorgao ( $r70_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhlota ";
     $sql .= "      left join rhlotaexe  on  rhlotaexe.rh26_codigo = rhlota.r70_codigo and rh26_anousu = ".db_getsession('DB_anousu');
     $sql .= "      left join orcorgao   on  orcorgao.o40_orgao = rhlotaexe.rh26_orgao and orcorgao.o40_anousu = rhlotaexe.rh26_anousu ";
     $sql2 = "";
     if($dbwhere==""){
       if($r70_codigo!=null ){
         $sql2 .= " where rhlota.r70_codigo = $r70_codigo "; 
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
  
  
  function sql_query_lota_cgm ( $r70_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

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
     $sql .= " from rhlota ";
     $sql .= "      inner join cgm on  cgm.z01_numcgm = rhlota.r70_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r70_codigo!=null ){
         $sql2 .= " where rhlota.r70_codigo = $r70_codigo ";
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
   function sql_query_orgao ( $r70_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhlota ";
     $sql .= "      inner join rhlotaexe  on  rhlotaexe.rh26_codigo = rhlota.r70_codigo ";
     $sql .= "      inner join orcorgao   on  orcorgao.o40_orgao = rhlotaexe.rh26_orgao and orcorgao.o40_anousu = rhlotaexe.rh26_anousu ";
     $sql2 = "";
     if($dbwhere==""){
       if($r70_codigo!=null ){
         $sql2 .= " where rhlota.r70_codigo = $r70_codigo "; 
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

  /**
   * Metodo criado para ser utilizado no relatorio de lotacao (relatorios->relatorios cadastrais->lotacoes) modulo rh pessoal.
   * Este metodo retorna uma string de sql para a consulta.
   * @param string $dAno
   * @param int $iInstituicao
   * @param string $sWhere
   * @param string $sOrdem
   * @return string
   */
	function sql_query_dadosElemento ($dAno, $iInstituicao, $sWhere, $sOrdem) {
		
		$sSql  = "select distinct                                                                                           ";
		$sSql .= "     rhlota.r70_estrut         ,                                                                          ";
		$sSql .= "     rhlota.r70_codigo         ,                                                                          ";
		$sSql .= "     rhlota.r70_descr          ,                                                                          ";
		$sSql .= "     orcorgao.o40_orgao        ,                                                                          ";
		$sSql .= "     orcorgao.o40_descr        ,                                                                          ";
		$sSql .= "     orcunidade.o41_orgao      ,                                                                          ";
		$sSql .= "     orcunidade.o41_unidade    ,                                                                          ";
		$sSql .= "     orcunidade.o41_descr      ,                                                                          ";
		$sSql .= "     orcprojativ.o55_projativ  ,                                                                          ";
		$sSql .= "     orcprojativ.o55_descr     ,                                                                          ";
		$sSql .= "     orctiporec.o15_codigo     ,                                                                          ";
		$sSql .= "     orctiporec.o15_descr      ,                                                                          ";
		$sSql .= "     orcfuncao.o52_funcao      ,                                                                          ";
		$sSql .= "     orcfuncao.o52_descr       ,                                                                          ";
		$sSql .= "     orcsubfuncao.o53_subfuncao,                                                                          ";
		$sSql .= "     orcsubfuncao.o53_descr    ,                                                                          ";
		$sSql .= "     orcprograma.o54_programa  ,                                                                          ";
		$sSql .= "     orcprograma.o54_descr     ,                                                                          ";
		$sSql .= "     concarpeculiar.c58_descr  ,                                                                          ";
		$sSql .= "     rh28_codeledef,                                                                                      ";
		$sSql .= "     elemento_novo.o56_codele   as o56_codele_novo,                                                       ";
		$sSql .= "     elemento_novo.o56_descr    as o56_descr_novo,                                                        ";
		$sSql .= "     recurso_novo.o15_codigo    as o15_codigo_novo,                                                       ";
		$sSql .= "     recurso_novo.o15_descr     as o15_descr_novo,                                                        ";
		$sSql .= "     projativ_novo.o55_projativ as o55_projativ_novo,                                                     ";
		$sSql .= "     projativ_novo.o55_descr    as o55_descr_novo,                                                        ";
		$sSql .= "     projativ_novo.o55_anousu   as o55_anousu_novo                                                        ";
		$sSql .= "from rhlota                                                                                               ";
		$sSql .= "      left join rhlotaexe                    on rh26_codigo              = r70_codigo                     ";
		$sSql .= "                                            and rh26_anousu              = {$dAno}                        ";
		$sSql .= "      left join orcorgao                     on o40_orgao                = rh26_orgao                     ";
		$sSql .= "                                            and o40_anousu               = rh26_anousu                    ";
		$sSql .= "                                            and o40_instit               = r70_instit                     ";
		$sSql .= "      left join orcunidade                   on rh26_unidade             = o41_unidade                    ";
		$sSql .= "                                            and rh26_orgao               = o41_orgao                      ";
		$sSql .= "                                            and rh26_anousu              = o41_anousu                     ";
		$sSql .= "      left join rhlotavinc                   on rh25_codigo              = r70_codigo                     ";
		$sSql .= "                                            and rh25_anousu              = {$dAno}                        ";
		$sSql .= "      left join orcprojativ                  on o55_projativ             = rh25_projativ                  ";
		$sSql .= "                                            and o55_anousu               = rh25_anousu                    ";
		$sSql .= "      left join orctiporec                   on o15_codigo               = rh25_recurso                   ";
		$sSql .= "      left join orcfuncao                    on o52_funcao               = rh25_funcao                    ";
		$sSql .= "      left join orcsubfuncao                 on o53_subfuncao            = rh25_subfuncao                 ";
		$sSql .= "      left join orcprograma                  on o54_programa             = rh25_programa                  ";
		$sSql .= "                                            and o54_anousu               = {$dAno}                        ";
		$sSql .= "      left join concarpeculiar               on r70_concarpeculiar       = c58_sequencial                 ";
		$sSql .= "      left join rhlotavincele                on rh28_codlotavinc         = rh25_codlotavinc               ";
		$sSql .= "      left join rhlotavincativ               on rh39_codlotavinc         = rh25_codlotavinc               ";
		$sSql .= "      left join orcelemento                  on orcelemento.o56_codele   = rhlotavincele.rh28_codeledef   ";
		$sSql .= "                                            and orcelemento.o56_anousu   = {$dAno}                        ";
		$sSql .= "      left join orcelemento as elemento_novo on elemento_novo.o56_codele = rhlotavincele.rh28_codelenov   ";
		$sSql .= "      left join rhlotavincativ as lvat       on lvat.rh39_codlotavinc    = rh25_codlotavinc               ";
		$sSql .= "                                            and lvat.rh39_codelenov      = rhlotavincele.rh28_codelenov   ";
		$sSql .= "      left join orcprojativ as projativ_novo on projativ_novo.o55_anousu = lvat.rh39_anousu               ";
		$sSql .= "                                            and projativ_novo.o55_projativ = lvat.rh39_projativ           ";
		$sSql .= "      left join rhlotavincrec                on rhlotavincrec.rh43_codelenov = lvat.rh39_codelenov        ";
		$sSql .= "                                            and rhlotavincrec.rh43_codlotavinc = rh25_codlotavinc         ";
		$sSql .= "      left join orctiporec as recurso_novo   on recurso_novo.o15_codigo = rhlotavincrec.rh43_recurso      ";
		$sSql .= "                                                                                                          ";
		$sSql .= "  where r70_instit = {$iInstituicao} {$sWhere}                                                            ";
		$sSql .= " order by  {$sOrdem}                                                                                      ";
		
		return $sSql;
		
	}

}
?>