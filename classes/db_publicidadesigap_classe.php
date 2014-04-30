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

//MODULO: contabilidade
//CLASSE DA ENTIDADE publicidadesigap
class cl_publicidadesigap {
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
   var $c48_sequencial = 0; 
   var $c48_meiocomunicacaosigap = 0; 
   var $c48_instit = 0; 
   var $c48_mes = 0; 
   var $c48_ano = 0; 
   var $c48_descricao = null; 
   var $c48_datapublicacao_dia = null; 
   var $c48_datapublicacao_mes = null; 
   var $c48_datapublicacao_ano = null; 
   var $c48_datapublicacao = null; 
   var $c48_tiporelatoriofiscal = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c48_sequencial = int4 = Código Sequencial 
                 c48_meiocomunicacaosigap = int4 = Meio de Comunicação 
                 c48_instit = int4 = Instituição 
                 c48_mes = int4 = Mês 
                 c48_ano = int4 = Ano 
                 c48_descricao = varchar(70) = Descrição 
                 c48_datapublicacao = date = Data de Publicação 
                 c48_tiporelatoriofiscal = int4 = Tipo Relatório Fiscal 
                 ";
   //funcao construtor da classe 
   function cl_publicidadesigap() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("publicidadesigap"); 
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
       $this->c48_sequencial = ($this->c48_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c48_sequencial"]:$this->c48_sequencial);
       $this->c48_meiocomunicacaosigap = ($this->c48_meiocomunicacaosigap == ""?@$GLOBALS["HTTP_POST_VARS"]["c48_meiocomunicacaosigap"]:$this->c48_meiocomunicacaosigap);
       $this->c48_instit = ($this->c48_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c48_instit"]:$this->c48_instit);
       $this->c48_mes = ($this->c48_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c48_mes"]:$this->c48_mes);
       $this->c48_ano = ($this->c48_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c48_ano"]:$this->c48_ano);
       $this->c48_descricao = ($this->c48_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["c48_descricao"]:$this->c48_descricao);
       if($this->c48_datapublicacao == ""){
         $this->c48_datapublicacao_dia = ($this->c48_datapublicacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c48_datapublicacao_dia"]:$this->c48_datapublicacao_dia);
         $this->c48_datapublicacao_mes = ($this->c48_datapublicacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c48_datapublicacao_mes"]:$this->c48_datapublicacao_mes);
         $this->c48_datapublicacao_ano = ($this->c48_datapublicacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c48_datapublicacao_ano"]:$this->c48_datapublicacao_ano);
         if($this->c48_datapublicacao_dia != ""){
            $this->c48_datapublicacao = $this->c48_datapublicacao_ano."-".$this->c48_datapublicacao_mes."-".$this->c48_datapublicacao_dia;
         }
       }
       $this->c48_tiporelatoriofiscal = ($this->c48_tiporelatoriofiscal == ""?@$GLOBALS["HTTP_POST_VARS"]["c48_tiporelatoriofiscal"]:$this->c48_tiporelatoriofiscal);
     }else{
       $this->c48_sequencial = ($this->c48_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c48_sequencial"]:$this->c48_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c48_sequencial){ 
      $this->atualizacampos();
     if($this->c48_meiocomunicacaosigap == null ){ 
       $this->erro_sql = " Campo Meio de Comunicação nao Informado.";
       $this->erro_campo = "c48_meiocomunicacaosigap";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c48_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "c48_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c48_mes == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "c48_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c48_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "c48_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c48_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "c48_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c48_datapublicacao == null ){ 
       $this->erro_sql = " Campo Data de Publicação nao Informado.";
       $this->erro_campo = "c48_datapublicacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c48_tiporelatoriofiscal == null ){ 
       $this->erro_sql = " Campo Tipo Relatório Fiscal nao Informado.";
       $this->erro_campo = "c48_tiporelatoriofiscal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c48_sequencial == "" || $c48_sequencial == null ){
       $result = db_query("select nextval('publicidadesigap_c48_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: publicidadesigap_c48_sequencial_seq do campo: c48_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c48_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from publicidadesigap_c48_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c48_sequencial)){
         $this->erro_sql = " Campo c48_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c48_sequencial = $c48_sequencial; 
       }
     }
     if(($this->c48_sequencial == null) || ($this->c48_sequencial == "") ){ 
       $this->erro_sql = " Campo c48_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into publicidadesigap(
                                       c48_sequencial 
                                      ,c48_meiocomunicacaosigap 
                                      ,c48_instit 
                                      ,c48_mes 
                                      ,c48_ano 
                                      ,c48_descricao 
                                      ,c48_datapublicacao 
                                      ,c48_tiporelatoriofiscal 
                       )
                values (
                                $this->c48_sequencial 
                               ,$this->c48_meiocomunicacaosigap 
                               ,$this->c48_instit 
                               ,$this->c48_mes 
                               ,$this->c48_ano 
                               ,'$this->c48_descricao' 
                               ,".($this->c48_datapublicacao == "null" || $this->c48_datapublicacao == ""?"null":"'".$this->c48_datapublicacao."'")." 
                               ,$this->c48_tiporelatoriofiscal 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "publicidadesigap ($this->c48_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "publicidadesigap já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "publicidadesigap ($this->c48_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c48_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c48_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17841,'$this->c48_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3150,17841,'','".AddSlashes(pg_result($resaco,0,'c48_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3150,17842,'','".AddSlashes(pg_result($resaco,0,'c48_meiocomunicacaosigap'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3150,17843,'','".AddSlashes(pg_result($resaco,0,'c48_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3150,17844,'','".AddSlashes(pg_result($resaco,0,'c48_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3150,17845,'','".AddSlashes(pg_result($resaco,0,'c48_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3150,17846,'','".AddSlashes(pg_result($resaco,0,'c48_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3150,17847,'','".AddSlashes(pg_result($resaco,0,'c48_datapublicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3150,17848,'','".AddSlashes(pg_result($resaco,0,'c48_tiporelatoriofiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c48_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update publicidadesigap set ";
     $virgula = "";
     if(trim($this->c48_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c48_sequencial"])){ 
       $sql  .= $virgula." c48_sequencial = $this->c48_sequencial ";
       $virgula = ",";
       if(trim($this->c48_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "c48_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c48_meiocomunicacaosigap)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c48_meiocomunicacaosigap"])){ 
       $sql  .= $virgula." c48_meiocomunicacaosigap = $this->c48_meiocomunicacaosigap ";
       $virgula = ",";
       if(trim($this->c48_meiocomunicacaosigap) == null ){ 
         $this->erro_sql = " Campo Meio de Comunicação nao Informado.";
         $this->erro_campo = "c48_meiocomunicacaosigap";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c48_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c48_instit"])){ 
       $sql  .= $virgula." c48_instit = $this->c48_instit ";
       $virgula = ",";
       if(trim($this->c48_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "c48_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c48_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c48_mes"])){ 
       $sql  .= $virgula." c48_mes = $this->c48_mes ";
       $virgula = ",";
       if(trim($this->c48_mes) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "c48_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c48_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c48_ano"])){ 
       $sql  .= $virgula." c48_ano = $this->c48_ano ";
       $virgula = ",";
       if(trim($this->c48_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "c48_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c48_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c48_descricao"])){ 
       $sql  .= $virgula." c48_descricao = '$this->c48_descricao' ";
       $virgula = ",";
       if(trim($this->c48_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "c48_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c48_datapublicacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c48_datapublicacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c48_datapublicacao_dia"] !="") ){ 
       $sql  .= $virgula." c48_datapublicacao = '$this->c48_datapublicacao' ";
       $virgula = ",";
       if(trim($this->c48_datapublicacao) == null ){ 
         $this->erro_sql = " Campo Data de Publicação nao Informado.";
         $this->erro_campo = "c48_datapublicacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c48_datapublicacao_dia"])){ 
         $sql  .= $virgula." c48_datapublicacao = null ";
         $virgula = ",";
         if(trim($this->c48_datapublicacao) == null ){ 
           $this->erro_sql = " Campo Data de Publicação nao Informado.";
           $this->erro_campo = "c48_datapublicacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c48_tiporelatoriofiscal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c48_tiporelatoriofiscal"])){ 
       $sql  .= $virgula." c48_tiporelatoriofiscal = $this->c48_tiporelatoriofiscal ";
       $virgula = ",";
       if(trim($this->c48_tiporelatoriofiscal) == null ){ 
         $this->erro_sql = " Campo Tipo Relatório Fiscal nao Informado.";
         $this->erro_campo = "c48_tiporelatoriofiscal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c48_sequencial!=null){
       $sql .= " c48_sequencial = $this->c48_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c48_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17841,'$this->c48_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c48_sequencial"]) || $this->c48_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3150,17841,'".AddSlashes(pg_result($resaco,$conresaco,'c48_sequencial'))."','$this->c48_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c48_meiocomunicacaosigap"]) || $this->c48_meiocomunicacaosigap != "")
           $resac = db_query("insert into db_acount values($acount,3150,17842,'".AddSlashes(pg_result($resaco,$conresaco,'c48_meiocomunicacaosigap'))."','$this->c48_meiocomunicacaosigap',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c48_instit"]) || $this->c48_instit != "")
           $resac = db_query("insert into db_acount values($acount,3150,17843,'".AddSlashes(pg_result($resaco,$conresaco,'c48_instit'))."','$this->c48_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c48_mes"]) || $this->c48_mes != "")
           $resac = db_query("insert into db_acount values($acount,3150,17844,'".AddSlashes(pg_result($resaco,$conresaco,'c48_mes'))."','$this->c48_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c48_ano"]) || $this->c48_ano != "")
           $resac = db_query("insert into db_acount values($acount,3150,17845,'".AddSlashes(pg_result($resaco,$conresaco,'c48_ano'))."','$this->c48_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c48_descricao"]) || $this->c48_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3150,17846,'".AddSlashes(pg_result($resaco,$conresaco,'c48_descricao'))."','$this->c48_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c48_datapublicacao"]) || $this->c48_datapublicacao != "")
           $resac = db_query("insert into db_acount values($acount,3150,17847,'".AddSlashes(pg_result($resaco,$conresaco,'c48_datapublicacao'))."','$this->c48_datapublicacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c48_tiporelatoriofiscal"]) || $this->c48_tiporelatoriofiscal != "")
           $resac = db_query("insert into db_acount values($acount,3150,17848,'".AddSlashes(pg_result($resaco,$conresaco,'c48_tiporelatoriofiscal'))."','$this->c48_tiporelatoriofiscal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "publicidadesigap nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c48_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "publicidadesigap nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c48_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c48_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c48_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c48_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17841,'$c48_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3150,17841,'','".AddSlashes(pg_result($resaco,$iresaco,'c48_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3150,17842,'','".AddSlashes(pg_result($resaco,$iresaco,'c48_meiocomunicacaosigap'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3150,17843,'','".AddSlashes(pg_result($resaco,$iresaco,'c48_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3150,17844,'','".AddSlashes(pg_result($resaco,$iresaco,'c48_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3150,17845,'','".AddSlashes(pg_result($resaco,$iresaco,'c48_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3150,17846,'','".AddSlashes(pg_result($resaco,$iresaco,'c48_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3150,17847,'','".AddSlashes(pg_result($resaco,$iresaco,'c48_datapublicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3150,17848,'','".AddSlashes(pg_result($resaco,$iresaco,'c48_tiporelatoriofiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from publicidadesigap
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c48_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c48_sequencial = $c48_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "publicidadesigap nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c48_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "publicidadesigap nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c48_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c48_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:publicidadesigap";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c48_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from publicidadesigap ";
     $sql .= "      inner join db_config  on  db_config.codigo = publicidadesigap.c48_instit";
     $sql .= "      inner join meiocomunicacaosigap  on  meiocomunicacaosigap.c49_sequencial = publicidadesigap.c48_meiocomunicacaosigap";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($c48_sequencial!=null ){
         $sql2 .= " where publicidadesigap.c48_sequencial = $c48_sequencial "; 
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
   function sql_query_file ( $c48_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from publicidadesigap ";
     $sql2 = "";
     if($dbwhere==""){
       if($c48_sequencial!=null ){
         $sql2 .= " where publicidadesigap.c48_sequencial = $c48_sequencial "; 
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