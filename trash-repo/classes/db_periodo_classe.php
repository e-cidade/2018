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

//MODULO: Configuracoes
//CLASSE DA ENTIDADE periodo
class cl_periodo { 
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
   var $o114_sequencial = 0; 
   var $o114_descricao = null; 
   var $o114_qdtporano = 0; 
   var $o114_diainicial = 0; 
   var $o114_mesinicial = 0; 
   var $o114_diafinal = 0; 
   var $o114_mesfinal = 0; 
   var $o114_sigla = null; 
   var $o114_ordem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o114_sequencial = int4 = Código Sequencial 
                 o114_descricao = varchar(20) = Descrição 
                 o114_qdtporano = int4 = Quantidade por Ano 
                 o114_diainicial = int4 = Dia Inicial 
                 o114_mesinicial = int4 = Mês Inicial 
                 o114_diafinal = int4 = Dia Final 
                 o114_mesfinal = int4 = Mês Final 
                 o114_sigla = varchar(10) = Sigla do Período 
                 o114_ordem = int4 = Ordem do Periodo 
                 ";
   //funcao construtor da classe 
   function cl_periodo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("periodo"); 
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
       $this->o114_sequencial = ($this->o114_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o114_sequencial"]:$this->o114_sequencial);
       $this->o114_descricao = ($this->o114_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["o114_descricao"]:$this->o114_descricao);
       $this->o114_qdtporano = ($this->o114_qdtporano == ""?@$GLOBALS["HTTP_POST_VARS"]["o114_qdtporano"]:$this->o114_qdtporano);
       $this->o114_diainicial = ($this->o114_diainicial == ""?@$GLOBALS["HTTP_POST_VARS"]["o114_diainicial"]:$this->o114_diainicial);
       $this->o114_mesinicial = ($this->o114_mesinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["o114_mesinicial"]:$this->o114_mesinicial);
       $this->o114_diafinal = ($this->o114_diafinal == ""?@$GLOBALS["HTTP_POST_VARS"]["o114_diafinal"]:$this->o114_diafinal);
       $this->o114_mesfinal = ($this->o114_mesfinal == ""?@$GLOBALS["HTTP_POST_VARS"]["o114_mesfinal"]:$this->o114_mesfinal);
       $this->o114_sigla = ($this->o114_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["o114_sigla"]:$this->o114_sigla);
       $this->o114_ordem = ($this->o114_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["o114_ordem"]:$this->o114_ordem);
     }else{
       $this->o114_sequencial = ($this->o114_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o114_sequencial"]:$this->o114_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o114_sequencial){ 
      $this->atualizacampos();
     if($this->o114_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "o114_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o114_qdtporano == null ){ 
       $this->erro_sql = " Campo Quantidade por Ano nao Informado.";
       $this->erro_campo = "o114_qdtporano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o114_diainicial == null ){ 
       $this->erro_sql = " Campo Dia Inicial nao Informado.";
       $this->erro_campo = "o114_diainicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o114_mesinicial == null ){ 
       $this->erro_sql = " Campo Mês Inicial nao Informado.";
       $this->erro_campo = "o114_mesinicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o114_diafinal == null ){ 
       $this->erro_sql = " Campo Dia Final nao Informado.";
       $this->erro_campo = "o114_diafinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o114_mesfinal == null ){ 
       $this->erro_sql = " Campo Mês Final nao Informado.";
       $this->erro_campo = "o114_mesfinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o114_ordem == null ){ 
       $this->o114_ordem = "0";
     }
     if($o114_sequencial == "" || $o114_sequencial == null ){
       $result = db_query("select nextval('periodo_o114_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: periodo_o114_sequencial_seq do campo: o114_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o114_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from periodo_o114_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o114_sequencial)){
         $this->erro_sql = " Campo o114_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o114_sequencial = $o114_sequencial; 
       }
     }
     if(($this->o114_sequencial == null) || ($this->o114_sequencial == "") ){ 
       $this->erro_sql = " Campo o114_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into periodo(
                                       o114_sequencial 
                                      ,o114_descricao 
                                      ,o114_qdtporano 
                                      ,o114_diainicial 
                                      ,o114_mesinicial 
                                      ,o114_diafinal 
                                      ,o114_mesfinal 
                                      ,o114_sigla 
                                      ,o114_ordem 
                       )
                values (
                                $this->o114_sequencial 
                               ,'$this->o114_descricao' 
                               ,$this->o114_qdtporano 
                               ,$this->o114_diainicial 
                               ,$this->o114_mesinicial 
                               ,$this->o114_diafinal 
                               ,$this->o114_mesfinal 
                               ,'$this->o114_sigla' 
                               ,$this->o114_ordem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cadastro de periodos ($this->o114_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cadastro de periodos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cadastro de periodos ($this->o114_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o114_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o114_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14105,'$this->o114_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2480,14105,'','".AddSlashes(pg_result($resaco,0,'o114_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2480,14106,'','".AddSlashes(pg_result($resaco,0,'o114_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2480,14109,'','".AddSlashes(pg_result($resaco,0,'o114_qdtporano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2480,14151,'','".AddSlashes(pg_result($resaco,0,'o114_diainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2480,14152,'','".AddSlashes(pg_result($resaco,0,'o114_mesinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2480,14153,'','".AddSlashes(pg_result($resaco,0,'o114_diafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2480,14154,'','".AddSlashes(pg_result($resaco,0,'o114_mesfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2480,15335,'','".AddSlashes(pg_result($resaco,0,'o114_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2480,15483,'','".AddSlashes(pg_result($resaco,0,'o114_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o114_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update periodo set ";
     $virgula = "";
     if(trim($this->o114_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o114_sequencial"])){ 
       $sql  .= $virgula." o114_sequencial = $this->o114_sequencial ";
       $virgula = ",";
       if(trim($this->o114_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o114_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o114_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o114_descricao"])){ 
       $sql  .= $virgula." o114_descricao = '$this->o114_descricao' ";
       $virgula = ",";
       if(trim($this->o114_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "o114_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o114_qdtporano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o114_qdtporano"])){ 
       $sql  .= $virgula." o114_qdtporano = $this->o114_qdtporano ";
       $virgula = ",";
       if(trim($this->o114_qdtporano) == null ){ 
         $this->erro_sql = " Campo Quantidade por Ano nao Informado.";
         $this->erro_campo = "o114_qdtporano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o114_diainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o114_diainicial"])){ 
       $sql  .= $virgula." o114_diainicial = $this->o114_diainicial ";
       $virgula = ",";
       if(trim($this->o114_diainicial) == null ){ 
         $this->erro_sql = " Campo Dia Inicial nao Informado.";
         $this->erro_campo = "o114_diainicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o114_mesinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o114_mesinicial"])){ 
       $sql  .= $virgula." o114_mesinicial = $this->o114_mesinicial ";
       $virgula = ",";
       if(trim($this->o114_mesinicial) == null ){ 
         $this->erro_sql = " Campo Mês Inicial nao Informado.";
         $this->erro_campo = "o114_mesinicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o114_diafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o114_diafinal"])){ 
       $sql  .= $virgula." o114_diafinal = $this->o114_diafinal ";
       $virgula = ",";
       if(trim($this->o114_diafinal) == null ){ 
         $this->erro_sql = " Campo Dia Final nao Informado.";
         $this->erro_campo = "o114_diafinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o114_mesfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o114_mesfinal"])){ 
       $sql  .= $virgula." o114_mesfinal = $this->o114_mesfinal ";
       $virgula = ",";
       if(trim($this->o114_mesfinal) == null ){ 
         $this->erro_sql = " Campo Mês Final nao Informado.";
         $this->erro_campo = "o114_mesfinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o114_sigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o114_sigla"])){ 
       $sql  .= $virgula." o114_sigla = '$this->o114_sigla' ";
       $virgula = ",";
     }
     if(trim($this->o114_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o114_ordem"])){ 
        if(trim($this->o114_ordem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o114_ordem"])){ 
           $this->o114_ordem = "0" ; 
        } 
       $sql  .= $virgula." o114_ordem = $this->o114_ordem ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o114_sequencial!=null){
       $sql .= " o114_sequencial = $this->o114_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o114_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14105,'$this->o114_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o114_sequencial"]) || $this->o114_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2480,14105,'".AddSlashes(pg_result($resaco,$conresaco,'o114_sequencial'))."','$this->o114_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o114_descricao"]) || $this->o114_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2480,14106,'".AddSlashes(pg_result($resaco,$conresaco,'o114_descricao'))."','$this->o114_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o114_qdtporano"]) || $this->o114_qdtporano != "")
           $resac = db_query("insert into db_acount values($acount,2480,14109,'".AddSlashes(pg_result($resaco,$conresaco,'o114_qdtporano'))."','$this->o114_qdtporano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o114_diainicial"]) || $this->o114_diainicial != "")
           $resac = db_query("insert into db_acount values($acount,2480,14151,'".AddSlashes(pg_result($resaco,$conresaco,'o114_diainicial'))."','$this->o114_diainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o114_mesinicial"]) || $this->o114_mesinicial != "")
           $resac = db_query("insert into db_acount values($acount,2480,14152,'".AddSlashes(pg_result($resaco,$conresaco,'o114_mesinicial'))."','$this->o114_mesinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o114_diafinal"]) || $this->o114_diafinal != "")
           $resac = db_query("insert into db_acount values($acount,2480,14153,'".AddSlashes(pg_result($resaco,$conresaco,'o114_diafinal'))."','$this->o114_diafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o114_mesfinal"]) || $this->o114_mesfinal != "")
           $resac = db_query("insert into db_acount values($acount,2480,14154,'".AddSlashes(pg_result($resaco,$conresaco,'o114_mesfinal'))."','$this->o114_mesfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o114_sigla"]) || $this->o114_sigla != "")
           $resac = db_query("insert into db_acount values($acount,2480,15335,'".AddSlashes(pg_result($resaco,$conresaco,'o114_sigla'))."','$this->o114_sigla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o114_ordem"]) || $this->o114_ordem != "")
           $resac = db_query("insert into db_acount values($acount,2480,15483,'".AddSlashes(pg_result($resaco,$conresaco,'o114_ordem'))."','$this->o114_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastro de periodos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o114_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro de periodos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o114_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o114_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o114_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o114_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14105,'$o114_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2480,14105,'','".AddSlashes(pg_result($resaco,$iresaco,'o114_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2480,14106,'','".AddSlashes(pg_result($resaco,$iresaco,'o114_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2480,14109,'','".AddSlashes(pg_result($resaco,$iresaco,'o114_qdtporano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2480,14151,'','".AddSlashes(pg_result($resaco,$iresaco,'o114_diainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2480,14152,'','".AddSlashes(pg_result($resaco,$iresaco,'o114_mesinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2480,14153,'','".AddSlashes(pg_result($resaco,$iresaco,'o114_diafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2480,14154,'','".AddSlashes(pg_result($resaco,$iresaco,'o114_mesfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2480,15335,'','".AddSlashes(pg_result($resaco,$iresaco,'o114_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2480,15483,'','".AddSlashes(pg_result($resaco,$iresaco,'o114_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from periodo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o114_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o114_sequencial = $o114_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastro de periodos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o114_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro de periodos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o114_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o114_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:periodo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o114_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from periodo ";
     $sql2 = "";
     if($dbwhere==""){
       if($o114_sequencial!=null ){
         $sql2 .= " where periodo.o114_sequencial = $o114_sequencial "; 
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
   function sql_query_file ( $o114_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from periodo ";
     $sql2 = "";
     if($dbwhere==""){
       if($o114_sequencial!=null ){
         $sql2 .= " where periodo.o114_sequencial = $o114_sequencial "; 
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