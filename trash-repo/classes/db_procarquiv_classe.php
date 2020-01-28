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
//CLASSE DA ENTIDADE procarquiv
class cl_procarquiv { 
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
   var $p67_codproc = 0; 
   var $p67_dtarq_dia = null; 
   var $p67_dtarq_mes = null; 
   var $p67_dtarq_ano = null; 
   var $p67_dtarq = null; 
   var $p67_historico = null; 
   var $p67_id_usuario = 0; 
   var $p67_coddepto = 0; 
   var $p67_codarquiv = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p67_codproc = int4 = Código do processo 
                 p67_dtarq = date = Data do Arquivamento 
                 p67_historico = text = Histórico do Arquivamento 
                 p67_id_usuario = int4 = Usuário 
                 p67_coddepto = int4 = Departamento 
                 p67_codarquiv = int4 = Código  do Arquivamento 
                 ";
   //funcao construtor da classe 
   function cl_procarquiv() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("procarquiv"); 
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
       $this->p67_codproc = ($this->p67_codproc == ""?@$GLOBALS["HTTP_POST_VARS"]["p67_codproc"]:$this->p67_codproc);
       if($this->p67_dtarq == ""){
         $this->p67_dtarq_dia = ($this->p67_dtarq_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p67_dtarq_dia"]:$this->p67_dtarq_dia);
         $this->p67_dtarq_mes = ($this->p67_dtarq_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p67_dtarq_mes"]:$this->p67_dtarq_mes);
         $this->p67_dtarq_ano = ($this->p67_dtarq_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p67_dtarq_ano"]:$this->p67_dtarq_ano);
         if($this->p67_dtarq_dia != ""){
            $this->p67_dtarq = $this->p67_dtarq_ano."-".$this->p67_dtarq_mes."-".$this->p67_dtarq_dia;
         }
       }
       $this->p67_historico = ($this->p67_historico == ""?@$GLOBALS["HTTP_POST_VARS"]["p67_historico"]:$this->p67_historico);
       $this->p67_id_usuario = ($this->p67_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["p67_id_usuario"]:$this->p67_id_usuario);
       $this->p67_coddepto = ($this->p67_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["p67_coddepto"]:$this->p67_coddepto);
       $this->p67_codarquiv = ($this->p67_codarquiv == ""?@$GLOBALS["HTTP_POST_VARS"]["p67_codarquiv"]:$this->p67_codarquiv);
     }else{
       $this->p67_codarquiv = ($this->p67_codarquiv == ""?@$GLOBALS["HTTP_POST_VARS"]["p67_codarquiv"]:$this->p67_codarquiv);
     }
   }
   // funcao para inclusao
   function incluir ($p67_codarquiv){ 
      $this->atualizacampos();
     if($this->p67_codproc == null ){ 
       $this->erro_sql = " Campo Código do processo nao Informado.";
       $this->erro_campo = "p67_codproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p67_dtarq == null ){ 
       $this->erro_sql = " Campo Data do Arquivamento nao Informado.";
       $this->erro_campo = "p67_dtarq_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p67_historico == null ){ 
       $this->erro_sql = " Campo Histórico do Arquivamento nao Informado.";
       $this->erro_campo = "p67_historico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p67_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "p67_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p67_coddepto == null ){ 
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "p67_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($p67_codarquiv == "" || $p67_codarquiv == null ){
       $result = db_query("select nextval('procarquiv_p67_codarquiv_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: procarquiv_p67_codarquiv_seq do campo: p67_codarquiv"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->p67_codarquiv = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from procarquiv_p67_codarquiv_seq");
       if(($result != false) && (pg_result($result,0,0) < $p67_codarquiv)){
         $this->erro_sql = " Campo p67_codarquiv maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p67_codarquiv = $p67_codarquiv; 
       }
     }
     if(($this->p67_codarquiv == null) || ($this->p67_codarquiv == "") ){ 
       $this->erro_sql = " Campo p67_codarquiv nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into procarquiv(
                                       p67_codproc 
                                      ,p67_dtarq 
                                      ,p67_historico 
                                      ,p67_id_usuario 
                                      ,p67_coddepto 
                                      ,p67_codarquiv 
                       )
                values (
                                $this->p67_codproc 
                               ,".($this->p67_dtarq == "null" || $this->p67_dtarq == ""?"null":"'".$this->p67_dtarq."'")." 
                               ,'$this->p67_historico' 
                               ,$this->p67_id_usuario 
                               ,$this->p67_coddepto 
                               ,$this->p67_codarquiv 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivamento dos Processos ($this->p67_codarquiv) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivamento dos Processos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivamento dos Processos ($this->p67_codarquiv) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p67_codarquiv;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->p67_codarquiv));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4682,'$this->p67_codarquiv','I')");
       $resac = db_query("insert into db_acount values($acount,615,4677,'','".AddSlashes(pg_result($resaco,0,'p67_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,615,4678,'','".AddSlashes(pg_result($resaco,0,'p67_dtarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,615,4679,'','".AddSlashes(pg_result($resaco,0,'p67_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,615,4680,'','".AddSlashes(pg_result($resaco,0,'p67_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,615,4681,'','".AddSlashes(pg_result($resaco,0,'p67_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,615,4682,'','".AddSlashes(pg_result($resaco,0,'p67_codarquiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p67_codarquiv=null) { 
      $this->atualizacampos();
     $sql = " update procarquiv set ";
     $virgula = "";
     if(trim($this->p67_codproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p67_codproc"])){ 
       $sql  .= $virgula." p67_codproc = $this->p67_codproc ";
       $virgula = ",";
       if(trim($this->p67_codproc) == null ){ 
         $this->erro_sql = " Campo Código do processo nao Informado.";
         $this->erro_campo = "p67_codproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p67_dtarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p67_dtarq_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p67_dtarq_dia"] !="") ){ 
       $sql  .= $virgula." p67_dtarq = '$this->p67_dtarq' ";
       $virgula = ",";
       if(trim($this->p67_dtarq) == null ){ 
         $this->erro_sql = " Campo Data do Arquivamento nao Informado.";
         $this->erro_campo = "p67_dtarq_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["p67_dtarq_dia"])){ 
         $sql  .= $virgula." p67_dtarq = null ";
         $virgula = ",";
         if(trim($this->p67_dtarq) == null ){ 
           $this->erro_sql = " Campo Data do Arquivamento nao Informado.";
           $this->erro_campo = "p67_dtarq_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->p67_historico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p67_historico"])){ 
       $sql  .= $virgula." p67_historico = '$this->p67_historico' ";
       $virgula = ",";
       if(trim($this->p67_historico) == null ){ 
         $this->erro_sql = " Campo Histórico do Arquivamento nao Informado.";
         $this->erro_campo = "p67_historico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p67_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p67_id_usuario"])){ 
       $sql  .= $virgula." p67_id_usuario = $this->p67_id_usuario ";
       $virgula = ",";
       if(trim($this->p67_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "p67_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p67_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p67_coddepto"])){ 
       $sql  .= $virgula." p67_coddepto = $this->p67_coddepto ";
       $virgula = ",";
       if(trim($this->p67_coddepto) == null ){ 
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "p67_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p67_codarquiv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p67_codarquiv"])){ 
       $sql  .= $virgula." p67_codarquiv = $this->p67_codarquiv ";
       $virgula = ",";
       if(trim($this->p67_codarquiv) == null ){ 
         $this->erro_sql = " Campo Código  do Arquivamento nao Informado.";
         $this->erro_campo = "p67_codarquiv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p67_codarquiv!=null){
       $sql .= " p67_codarquiv = $this->p67_codarquiv";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->p67_codarquiv));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4682,'$this->p67_codarquiv','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p67_codproc"]))
           $resac = db_query("insert into db_acount values($acount,615,4677,'".AddSlashes(pg_result($resaco,$conresaco,'p67_codproc'))."','$this->p67_codproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p67_dtarq"]))
           $resac = db_query("insert into db_acount values($acount,615,4678,'".AddSlashes(pg_result($resaco,$conresaco,'p67_dtarq'))."','$this->p67_dtarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p67_historico"]))
           $resac = db_query("insert into db_acount values($acount,615,4679,'".AddSlashes(pg_result($resaco,$conresaco,'p67_historico'))."','$this->p67_historico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p67_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,615,4680,'".AddSlashes(pg_result($resaco,$conresaco,'p67_id_usuario'))."','$this->p67_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p67_coddepto"]))
           $resac = db_query("insert into db_acount values($acount,615,4681,'".AddSlashes(pg_result($resaco,$conresaco,'p67_coddepto'))."','$this->p67_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p67_codarquiv"]))
           $resac = db_query("insert into db_acount values($acount,615,4682,'".AddSlashes(pg_result($resaco,$conresaco,'p67_codarquiv'))."','$this->p67_codarquiv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivamento dos Processos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p67_codarquiv;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivamento dos Processos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p67_codarquiv;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p67_codarquiv;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p67_codarquiv=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($p67_codarquiv));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4682,'$p67_codarquiv','E')");
         $resac = db_query("insert into db_acount values($acount,615,4677,'','".AddSlashes(pg_result($resaco,$iresaco,'p67_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,615,4678,'','".AddSlashes(pg_result($resaco,$iresaco,'p67_dtarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,615,4679,'','".AddSlashes(pg_result($resaco,$iresaco,'p67_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,615,4680,'','".AddSlashes(pg_result($resaco,$iresaco,'p67_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,615,4681,'','".AddSlashes(pg_result($resaco,$iresaco,'p67_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,615,4682,'','".AddSlashes(pg_result($resaco,$iresaco,'p67_codarquiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from procarquiv
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p67_codarquiv != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p67_codarquiv = $p67_codarquiv ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivamento dos Processos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p67_codarquiv;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivamento dos Processos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p67_codarquiv;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p67_codarquiv;
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
        $this->erro_sql   = "Record Vazio na Tabela:procarquiv";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $p67_codarquiv=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procarquiv ";
     $sql .= " inner join protprocesso on p58_codproc = p67_codproc";
     $sql2 = "";
     if($dbwhere==""){
       if($p67_codarquiv!=null ){
         $sql2 .= " where procarquiv.p67_codarquiv = $p67_codarquiv "; 
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
   function sql_query_file ( $p67_codarquiv=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procarquiv ";
     $sql2 = "";
     if($dbwhere==""){
       if($p67_codarquiv!=null ){
         $sql2 .= " where procarquiv.p67_codarquiv = $p67_codarquiv "; 
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
  
  function sql_query_ouvprocarquivado ( $p67_codarquiv=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     
     $sql .= " from procarquiv                                                                                            ";
     $sql .= "      inner join protprocesso         on protprocesso.p58_codproc              = procarquiv.p67_codproc     ";
     $sql .= "      inner join cgm                  on cgm.z01_numcgm                        = protprocesso.p58_numcgm    ";
     $sql .= "      inner join arqproc              on arqproc.p68_codarquiv                 = procarquiv.p67_codarquiv   ";
     $sql .= "      left  join processoouvidoria    on processoouvidoria.ov09_protprocesso   = protprocesso.p58_codproc   ";
     $sql .= "      left  join ouvidoriaatendimento on ouvidoriaatendimento.ov01_sequencial  = ov09_ouvidoriaatendimento  ";
     $sql .= "      inner join tipoproc             on tipoproc.p51_codigo                   = protprocesso.p58_codigo    ";
     
     if($dbwhere==""){
       if($p67_codarquiv!=null ){
         $sql2 .= " where procarquiv.p67_codarquiv = $p67_codarquiv "; 
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