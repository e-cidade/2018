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
//CLASSE DA ENTIDADE rubricas
class cl_rubricas { 
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
   var $r06_anousu = 0; 
   var $r06_mesusu = 0; 
   var $r06_codigo = null; 
   var $r06_descr = null; 
   var $r06_pd = 'f'; 
   var $r06_form = null; 
   var $r06_quant = 0; 
   var $r06_empsn = null; 
   var $r06_calc = 0; 
   var $r06_calc1 = null; 
   var $r06_calc2 = null; 
   var $r06_calc3 = null; 
   var $r06_tipo = null; 
   var $r06_limdat = null; 
   var $r06_elemen = null; 
   var $r06_obs = null; 
   var $r06_cond2 = null; 
   var $r06_cond3 = null; 
   var $r06_form2 = null; 
   var $r06_form3 = null; 
   var $r06_calcp = 'f'; 
   var $r06_propq = 'f'; 
   var $r06_coluna = 0; 
   var $r06_presta = 'f'; 
   var $r06_efetiv = 'f'; 
   var $r06_formq = null; 
   var $r06_ccheq = 'f'; 
   var $r06_repos = 'f'; 
   var $r06_propi = 'f'; 
   var $r06_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r06_anousu = int4 = Ano do Exercicio 
                 r06_mesusu = int4 = Mes do Exercicio 
                 r06_codigo = char(4) = Codigo da Rubrica 
                 r06_descr = char(30) = Descricao do Código 
                 r06_pd = bool = Identifica se e Provento, Desconto ou Base 
                 r06_form = char(120) = Formula para Calculo da Rubric 
                 r06_quant = float8 = Qtda ou Valor para inicializar 
                 r06_empsn = char(     1) = Indica se vai s/Empenhada s/n 
                 r06_calc = int4 = Seta o tipo de calculo 
                 r06_calc1 = char(     1) = Incidencia para calc Ferias 
                 r06_calc2 = char(     1) = incidencia para calc 13 sal 
                 r06_calc3 = char(     1) = incidencia para calc. Rescisao 
                 r06_tipo = char(     1) = Tipo de inicializacao 
                 r06_limdat = char(     1) = s/n (para data limite) 
                 r06_elemen = char(12) = Elemento da rubrica orcamentar 
                 r06_obs = char(   120) = Obs.da rubrica na digit.ponto 
                 r06_cond2 = char(120) = Condicao da formula 2 
                 r06_cond3 = char(120) = Condicao da formula 3 
                 r06_form2 = char(120) = Formula condicao 2 
                 r06_form3 = char(120) = Formula condicao 3 
                 r06_calcp = boolean = Calcula proporc.nos afastament 
                 r06_propq = boolean = Grava qtd.proporc.nos afastam. 
                 r06_coluna = int4 = Coluna do rel 36 contribuicoes 
                 r06_presta = boolean = Calcula nr.prestacoes que falt 
                 r06_efetiv = boolean = marcar para efetividade 
                 r06_formq = char(120) = formula para apresentac.qtd 
                 r06_ccheq = boolean = lancar qtd no contra-cheque? 
                 r06_repos = boolean = rubrica de reposicao? 
                 r06_propi = bool = Proporção 
                 r06_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_rubricas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rubricas"); 
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
       $this->r06_anousu = ($this->r06_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_anousu"]:$this->r06_anousu);
       $this->r06_mesusu = ($this->r06_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_mesusu"]:$this->r06_mesusu);
       $this->r06_codigo = ($this->r06_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_codigo"]:$this->r06_codigo);
       $this->r06_descr = ($this->r06_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_descr"]:$this->r06_descr);
       $this->r06_pd = ($this->r06_pd == "f"?@$GLOBALS["HTTP_POST_VARS"]["r06_pd"]:$this->r06_pd);
       $this->r06_form = ($this->r06_form == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_form"]:$this->r06_form);
       $this->r06_quant = ($this->r06_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_quant"]:$this->r06_quant);
       $this->r06_empsn = ($this->r06_empsn == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_empsn"]:$this->r06_empsn);
       $this->r06_calc = ($this->r06_calc == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_calc"]:$this->r06_calc);
       $this->r06_calc1 = ($this->r06_calc1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_calc1"]:$this->r06_calc1);
       $this->r06_calc2 = ($this->r06_calc2 == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_calc2"]:$this->r06_calc2);
       $this->r06_calc3 = ($this->r06_calc3 == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_calc3"]:$this->r06_calc3);
       $this->r06_tipo = ($this->r06_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_tipo"]:$this->r06_tipo);
       $this->r06_limdat = ($this->r06_limdat == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_limdat"]:$this->r06_limdat);
       $this->r06_elemen = ($this->r06_elemen == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_elemen"]:$this->r06_elemen);
       $this->r06_obs = ($this->r06_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_obs"]:$this->r06_obs);
       $this->r06_cond2 = ($this->r06_cond2 == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_cond2"]:$this->r06_cond2);
       $this->r06_cond3 = ($this->r06_cond3 == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_cond3"]:$this->r06_cond3);
       $this->r06_form2 = ($this->r06_form2 == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_form2"]:$this->r06_form2);
       $this->r06_form3 = ($this->r06_form3 == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_form3"]:$this->r06_form3);
       $this->r06_calcp = ($this->r06_calcp == "f"?@$GLOBALS["HTTP_POST_VARS"]["r06_calcp"]:$this->r06_calcp);
       $this->r06_propq = ($this->r06_propq == "f"?@$GLOBALS["HTTP_POST_VARS"]["r06_propq"]:$this->r06_propq);
       $this->r06_coluna = ($this->r06_coluna == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_coluna"]:$this->r06_coluna);
       $this->r06_presta = ($this->r06_presta == "f"?@$GLOBALS["HTTP_POST_VARS"]["r06_presta"]:$this->r06_presta);
       $this->r06_efetiv = ($this->r06_efetiv == "f"?@$GLOBALS["HTTP_POST_VARS"]["r06_efetiv"]:$this->r06_efetiv);
       $this->r06_formq = ($this->r06_formq == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_formq"]:$this->r06_formq);
       $this->r06_ccheq = ($this->r06_ccheq == "f"?@$GLOBALS["HTTP_POST_VARS"]["r06_ccheq"]:$this->r06_ccheq);
       $this->r06_repos = ($this->r06_repos == "f"?@$GLOBALS["HTTP_POST_VARS"]["r06_repos"]:$this->r06_repos);
       $this->r06_propi = ($this->r06_propi == "f"?@$GLOBALS["HTTP_POST_VARS"]["r06_propi"]:$this->r06_propi);
       $this->r06_instit = ($this->r06_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_instit"]:$this->r06_instit);
     }else{
       $this->r06_anousu = ($this->r06_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_anousu"]:$this->r06_anousu);
       $this->r06_mesusu = ($this->r06_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_mesusu"]:$this->r06_mesusu);
       $this->r06_codigo = ($this->r06_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r06_codigo"]:$this->r06_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($r06_anousu,$r06_mesusu,$r06_codigo){ 
      $this->atualizacampos();
     if($this->r06_pd == null ){ 
       $this->r06_pd = "f";
     }
     if($this->r06_propi == null ){ 
       $this->r06_propi = "f";
     }
     if($this->r06_instit == null ){ 
       $this->r06_instit = "0";
     }
       $this->r06_anousu = $r06_anousu; 
       $this->r06_mesusu = $r06_mesusu; 
       $this->r06_codigo = $r06_codigo; 
     if(($this->r06_anousu == null) || ($this->r06_anousu == "") ){ 
       $this->erro_sql = " Campo r06_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r06_mesusu == null) || ($this->r06_mesusu == "") ){ 
       $this->erro_sql = " Campo r06_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r06_codigo == null) || ($this->r06_codigo == "") ){ 
       $this->erro_sql = " Campo r06_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rubricas(
                                       r06_anousu 
                                      ,r06_mesusu 
                                      ,r06_codigo 
                                      ,r06_descr 
                                      ,r06_pd 
                                      ,r06_form 
                                      ,r06_quant 
                                      ,r06_empsn 
                                      ,r06_calc 
                                      ,r06_calc1 
                                      ,r06_calc2 
                                      ,r06_calc3 
                                      ,r06_tipo 
                                      ,r06_limdat 
                                      ,r06_elemen 
                                      ,r06_obs 
                                      ,r06_cond2 
                                      ,r06_cond3 
                                      ,r06_form2 
                                      ,r06_form3 
                                      ,r06_calcp 
                                      ,r06_propq 
                                      ,r06_coluna 
                                      ,r06_presta 
                                      ,r06_efetiv 
                                      ,r06_formq 
                                      ,r06_ccheq 
                                      ,r06_repos 
                                      ,r06_propi 
                                      ,r06_instit 
                       )
                values (
                                $this->r06_anousu 
                               ,$this->r06_mesusu 
                               ,'$this->r06_codigo' 
                               ,'$this->r06_descr' 
                               ,'$this->r06_pd' 
                               ,'$this->r06_form' 
                               ,$this->r06_quant 
                               ,'$this->r06_empsn' 
                               ,$this->r06_calc 
                               ,'$this->r06_calc1' 
                               ,'$this->r06_calc2' 
                               ,'$this->r06_calc3' 
                               ,'$this->r06_tipo' 
                               ,'$this->r06_limdat' 
                               ,'$this->r06_elemen' 
                               ,'$this->r06_obs' 
                               ,'$this->r06_cond2' 
                               ,'$this->r06_cond3' 
                               ,'$this->r06_form2' 
                               ,'$this->r06_form3' 
                               ,'$this->r06_calcp' 
                               ,'$this->r06_propq' 
                               ,$this->r06_coluna 
                               ,'$this->r06_presta' 
                               ,'$this->r06_efetiv' 
                               ,'$this->r06_formq' 
                               ,'$this->r06_ccheq' 
                               ,'$this->r06_repos' 
                               ,'$this->r06_propi' 
                               ,$this->r06_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastramento dos Codigos ($this->r06_anousu."-".$this->r06_mesusu."-".$this->r06_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastramento dos Codigos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastramento dos Codigos ($this->r06_anousu."-".$this->r06_mesusu."-".$this->r06_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r06_anousu."-".$this->r06_mesusu."-".$this->r06_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r06_anousu,$this->r06_mesusu,$this->r06_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4443,'$this->r06_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4444,'$this->r06_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4445,'$this->r06_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,590,4443,'','".AddSlashes(pg_result($resaco,0,'r06_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4444,'','".AddSlashes(pg_result($resaco,0,'r06_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4445,'','".AddSlashes(pg_result($resaco,0,'r06_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4446,'','".AddSlashes(pg_result($resaco,0,'r06_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4447,'','".AddSlashes(pg_result($resaco,0,'r06_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4448,'','".AddSlashes(pg_result($resaco,0,'r06_form'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4449,'','".AddSlashes(pg_result($resaco,0,'r06_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4450,'','".AddSlashes(pg_result($resaco,0,'r06_empsn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4451,'','".AddSlashes(pg_result($resaco,0,'r06_calc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4452,'','".AddSlashes(pg_result($resaco,0,'r06_calc1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4453,'','".AddSlashes(pg_result($resaco,0,'r06_calc2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4454,'','".AddSlashes(pg_result($resaco,0,'r06_calc3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4455,'','".AddSlashes(pg_result($resaco,0,'r06_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4456,'','".AddSlashes(pg_result($resaco,0,'r06_limdat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4457,'','".AddSlashes(pg_result($resaco,0,'r06_elemen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4458,'','".AddSlashes(pg_result($resaco,0,'r06_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4459,'','".AddSlashes(pg_result($resaco,0,'r06_cond2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4460,'','".AddSlashes(pg_result($resaco,0,'r06_cond3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4461,'','".AddSlashes(pg_result($resaco,0,'r06_form2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4462,'','".AddSlashes(pg_result($resaco,0,'r06_form3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4463,'','".AddSlashes(pg_result($resaco,0,'r06_calcp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4464,'','".AddSlashes(pg_result($resaco,0,'r06_propq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4465,'','".AddSlashes(pg_result($resaco,0,'r06_coluna'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4466,'','".AddSlashes(pg_result($resaco,0,'r06_presta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4467,'','".AddSlashes(pg_result($resaco,0,'r06_efetiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4468,'','".AddSlashes(pg_result($resaco,0,'r06_formq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4469,'','".AddSlashes(pg_result($resaco,0,'r06_ccheq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4470,'','".AddSlashes(pg_result($resaco,0,'r06_repos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,4611,'','".AddSlashes(pg_result($resaco,0,'r06_propi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,590,7632,'','".AddSlashes(pg_result($resaco,0,'r06_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r06_anousu=null,$r06_mesusu=null,$r06_codigo=null) { 
      $this->atualizacampos();
     $sql = " update rubricas set ";
     $virgula = "";
     if(trim($this->r06_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_anousu"])){ 
        if(trim($this->r06_anousu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r06_anousu"])){ 
           $this->r06_anousu = "0" ; 
        } 
       $sql  .= $virgula." r06_anousu = $this->r06_anousu ";
       $virgula = ",";
     }
     if(trim($this->r06_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_mesusu"])){ 
        if(trim($this->r06_mesusu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r06_mesusu"])){ 
           $this->r06_mesusu = "0" ; 
        } 
       $sql  .= $virgula." r06_mesusu = $this->r06_mesusu ";
       $virgula = ",";
     }
     if(trim($this->r06_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_codigo"])){ 
       $sql  .= $virgula." r06_codigo = '$this->r06_codigo' ";
       $virgula = ",";
     }
     if(trim($this->r06_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_descr"])){ 
       $sql  .= $virgula." r06_descr = '$this->r06_descr' ";
       $virgula = ",";
     }
     if(trim($this->r06_pd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_pd"])){ 
       $sql  .= $virgula." r06_pd = '$this->r06_pd' ";
       $virgula = ",";
     }
     if(trim($this->r06_form)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_form"])){ 
       $sql  .= $virgula." r06_form = '$this->r06_form' ";
       $virgula = ",";
     }
     if(trim($this->r06_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_quant"])){ 
        if(trim($this->r06_quant)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r06_quant"])){ 
           $this->r06_quant = "0" ; 
        } 
       $sql  .= $virgula." r06_quant = $this->r06_quant ";
       $virgula = ",";
     }
     if(trim($this->r06_empsn)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_empsn"])){ 
       $sql  .= $virgula." r06_empsn = '$this->r06_empsn' ";
       $virgula = ",";
     }
     if(trim($this->r06_calc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_calc"])){ 
        if(trim($this->r06_calc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r06_calc"])){ 
           $this->r06_calc = "0" ; 
        } 
       $sql  .= $virgula." r06_calc = $this->r06_calc ";
       $virgula = ",";
     }
     if(trim($this->r06_calc1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_calc1"])){ 
       $sql  .= $virgula." r06_calc1 = '$this->r06_calc1' ";
       $virgula = ",";
     }
     if(trim($this->r06_calc2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_calc2"])){ 
       $sql  .= $virgula." r06_calc2 = '$this->r06_calc2' ";
       $virgula = ",";
     }
     if(trim($this->r06_calc3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_calc3"])){ 
       $sql  .= $virgula." r06_calc3 = '$this->r06_calc3' ";
       $virgula = ",";
     }
     if(trim($this->r06_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_tipo"])){ 
       $sql  .= $virgula." r06_tipo = '$this->r06_tipo' ";
       $virgula = ",";
     }
     if(trim($this->r06_limdat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_limdat"])){ 
       $sql  .= $virgula." r06_limdat = '$this->r06_limdat' ";
       $virgula = ",";
     }
     if(trim($this->r06_elemen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_elemen"])){ 
       $sql  .= $virgula." r06_elemen = '$this->r06_elemen' ";
       $virgula = ",";
     }
     if(trim($this->r06_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_obs"])){ 
       $sql  .= $virgula." r06_obs = '$this->r06_obs' ";
       $virgula = ",";
     }
     if(trim($this->r06_cond2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_cond2"])){ 
       $sql  .= $virgula." r06_cond2 = '$this->r06_cond2' ";
       $virgula = ",";
     }
     if(trim($this->r06_cond3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_cond3"])){ 
       $sql  .= $virgula." r06_cond3 = '$this->r06_cond3' ";
       $virgula = ",";
     }
     if(trim($this->r06_form2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_form2"])){ 
       $sql  .= $virgula." r06_form2 = '$this->r06_form2' ";
       $virgula = ",";
     }
     if(trim($this->r06_form3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_form3"])){ 
       $sql  .= $virgula." r06_form3 = '$this->r06_form3' ";
       $virgula = ",";
     }
     if(trim($this->r06_calcp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_calcp"])){ 
       $sql  .= $virgula." r06_calcp = '$this->r06_calcp' ";
       $virgula = ",";
     }
     if(trim($this->r06_propq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_propq"])){ 
       $sql  .= $virgula." r06_propq = '$this->r06_propq' ";
       $virgula = ",";
     }
     if(trim($this->r06_coluna)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_coluna"])){ 
        if(trim($this->r06_coluna)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r06_coluna"])){ 
           $this->r06_coluna = "0" ; 
        } 
       $sql  .= $virgula." r06_coluna = $this->r06_coluna ";
       $virgula = ",";
     }
     if(trim($this->r06_presta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_presta"])){ 
       $sql  .= $virgula." r06_presta = '$this->r06_presta' ";
       $virgula = ",";
     }
     if(trim($this->r06_efetiv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_efetiv"])){ 
       $sql  .= $virgula." r06_efetiv = '$this->r06_efetiv' ";
       $virgula = ",";
     }
     if(trim($this->r06_formq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_formq"])){ 
       $sql  .= $virgula." r06_formq = '$this->r06_formq' ";
       $virgula = ",";
     }
     if(trim($this->r06_ccheq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_ccheq"])){ 
       $sql  .= $virgula." r06_ccheq = '$this->r06_ccheq' ";
       $virgula = ",";
     }
     if(trim($this->r06_repos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_repos"])){ 
       $sql  .= $virgula." r06_repos = '$this->r06_repos' ";
       $virgula = ",";
     }
     if(trim($this->r06_propi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_propi"])){ 
       $sql  .= $virgula." r06_propi = '$this->r06_propi' ";
       $virgula = ",";
     }
     if(trim($this->r06_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r06_instit"])){ 
        if(trim($this->r06_instit)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r06_instit"])){ 
           $this->r06_instit = "0" ; 
        } 
       $sql  .= $virgula." r06_instit = $this->r06_instit ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($r06_anousu!=null){
       $sql .= " r06_anousu = $this->r06_anousu";
     }
     if($r06_mesusu!=null){
       $sql .= " and  r06_mesusu = $this->r06_mesusu";
     }
     if($r06_codigo!=null){
       $sql .= " and  r06_codigo = '$this->r06_codigo'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r06_anousu,$this->r06_mesusu,$this->r06_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4443,'$this->r06_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4444,'$this->r06_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4445,'$this->r06_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_anousu"]) || $this->r06_anousu != "")
           $resac = db_query("insert into db_acount values($acount,590,4443,'".AddSlashes(pg_result($resaco,$conresaco,'r06_anousu'))."','$this->r06_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_mesusu"]) || $this->r06_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,590,4444,'".AddSlashes(pg_result($resaco,$conresaco,'r06_mesusu'))."','$this->r06_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_codigo"]) || $this->r06_codigo != "")
           $resac = db_query("insert into db_acount values($acount,590,4445,'".AddSlashes(pg_result($resaco,$conresaco,'r06_codigo'))."','$this->r06_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_descr"]) || $this->r06_descr != "")
           $resac = db_query("insert into db_acount values($acount,590,4446,'".AddSlashes(pg_result($resaco,$conresaco,'r06_descr'))."','$this->r06_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_pd"]) || $this->r06_pd != "")
           $resac = db_query("insert into db_acount values($acount,590,4447,'".AddSlashes(pg_result($resaco,$conresaco,'r06_pd'))."','$this->r06_pd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_form"]) || $this->r06_form != "")
           $resac = db_query("insert into db_acount values($acount,590,4448,'".AddSlashes(pg_result($resaco,$conresaco,'r06_form'))."','$this->r06_form',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_quant"]) || $this->r06_quant != "")
           $resac = db_query("insert into db_acount values($acount,590,4449,'".AddSlashes(pg_result($resaco,$conresaco,'r06_quant'))."','$this->r06_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_empsn"]) || $this->r06_empsn != "")
           $resac = db_query("insert into db_acount values($acount,590,4450,'".AddSlashes(pg_result($resaco,$conresaco,'r06_empsn'))."','$this->r06_empsn',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_calc"]) || $this->r06_calc != "")
           $resac = db_query("insert into db_acount values($acount,590,4451,'".AddSlashes(pg_result($resaco,$conresaco,'r06_calc'))."','$this->r06_calc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_calc1"]) || $this->r06_calc1 != "")
           $resac = db_query("insert into db_acount values($acount,590,4452,'".AddSlashes(pg_result($resaco,$conresaco,'r06_calc1'))."','$this->r06_calc1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_calc2"]) || $this->r06_calc2 != "")
           $resac = db_query("insert into db_acount values($acount,590,4453,'".AddSlashes(pg_result($resaco,$conresaco,'r06_calc2'))."','$this->r06_calc2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_calc3"]) || $this->r06_calc3 != "")
           $resac = db_query("insert into db_acount values($acount,590,4454,'".AddSlashes(pg_result($resaco,$conresaco,'r06_calc3'))."','$this->r06_calc3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_tipo"]) || $this->r06_tipo != "")
           $resac = db_query("insert into db_acount values($acount,590,4455,'".AddSlashes(pg_result($resaco,$conresaco,'r06_tipo'))."','$this->r06_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_limdat"]) || $this->r06_limdat != "")
           $resac = db_query("insert into db_acount values($acount,590,4456,'".AddSlashes(pg_result($resaco,$conresaco,'r06_limdat'))."','$this->r06_limdat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_elemen"]) || $this->r06_elemen != "")
           $resac = db_query("insert into db_acount values($acount,590,4457,'".AddSlashes(pg_result($resaco,$conresaco,'r06_elemen'))."','$this->r06_elemen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_obs"]) || $this->r06_obs != "")
           $resac = db_query("insert into db_acount values($acount,590,4458,'".AddSlashes(pg_result($resaco,$conresaco,'r06_obs'))."','$this->r06_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_cond2"]) || $this->r06_cond2 != "")
           $resac = db_query("insert into db_acount values($acount,590,4459,'".AddSlashes(pg_result($resaco,$conresaco,'r06_cond2'))."','$this->r06_cond2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_cond3"]) || $this->r06_cond3 != "")
           $resac = db_query("insert into db_acount values($acount,590,4460,'".AddSlashes(pg_result($resaco,$conresaco,'r06_cond3'))."','$this->r06_cond3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_form2"]) || $this->r06_form2 != "")
           $resac = db_query("insert into db_acount values($acount,590,4461,'".AddSlashes(pg_result($resaco,$conresaco,'r06_form2'))."','$this->r06_form2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_form3"]) || $this->r06_form3 != "")
           $resac = db_query("insert into db_acount values($acount,590,4462,'".AddSlashes(pg_result($resaco,$conresaco,'r06_form3'))."','$this->r06_form3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_calcp"]) || $this->r06_calcp != "")
           $resac = db_query("insert into db_acount values($acount,590,4463,'".AddSlashes(pg_result($resaco,$conresaco,'r06_calcp'))."','$this->r06_calcp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_propq"]) || $this->r06_propq != "")
           $resac = db_query("insert into db_acount values($acount,590,4464,'".AddSlashes(pg_result($resaco,$conresaco,'r06_propq'))."','$this->r06_propq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_coluna"]) || $this->r06_coluna != "")
           $resac = db_query("insert into db_acount values($acount,590,4465,'".AddSlashes(pg_result($resaco,$conresaco,'r06_coluna'))."','$this->r06_coluna',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_presta"]) || $this->r06_presta != "")
           $resac = db_query("insert into db_acount values($acount,590,4466,'".AddSlashes(pg_result($resaco,$conresaco,'r06_presta'))."','$this->r06_presta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_efetiv"]) || $this->r06_efetiv != "")
           $resac = db_query("insert into db_acount values($acount,590,4467,'".AddSlashes(pg_result($resaco,$conresaco,'r06_efetiv'))."','$this->r06_efetiv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_formq"]) || $this->r06_formq != "")
           $resac = db_query("insert into db_acount values($acount,590,4468,'".AddSlashes(pg_result($resaco,$conresaco,'r06_formq'))."','$this->r06_formq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_ccheq"]) || $this->r06_ccheq != "")
           $resac = db_query("insert into db_acount values($acount,590,4469,'".AddSlashes(pg_result($resaco,$conresaco,'r06_ccheq'))."','$this->r06_ccheq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_repos"]) || $this->r06_repos != "")
           $resac = db_query("insert into db_acount values($acount,590,4470,'".AddSlashes(pg_result($resaco,$conresaco,'r06_repos'))."','$this->r06_repos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_propi"]) || $this->r06_propi != "")
           $resac = db_query("insert into db_acount values($acount,590,4611,'".AddSlashes(pg_result($resaco,$conresaco,'r06_propi'))."','$this->r06_propi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r06_instit"]) || $this->r06_instit != "")
           $resac = db_query("insert into db_acount values($acount,590,7632,'".AddSlashes(pg_result($resaco,$conresaco,'r06_instit'))."','$this->r06_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastramento dos Codigos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r06_anousu."-".$this->r06_mesusu."-".$this->r06_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastramento dos Codigos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r06_anousu."-".$this->r06_mesusu."-".$this->r06_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r06_anousu."-".$this->r06_mesusu."-".$this->r06_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r06_anousu=null,$r06_mesusu=null,$r06_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r06_anousu,$r06_mesusu,$r06_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4443,'$r06_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4444,'$r06_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4445,'$r06_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,590,4443,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4444,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4445,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4446,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4447,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4448,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_form'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4449,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4450,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_empsn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4451,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_calc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4452,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_calc1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4453,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_calc2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4454,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_calc3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4455,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4456,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_limdat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4457,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_elemen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4458,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4459,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_cond2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4460,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_cond3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4461,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_form2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4462,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_form3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4463,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_calcp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4464,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_propq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4465,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_coluna'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4466,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_presta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4467,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_efetiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4468,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_formq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4469,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_ccheq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4470,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_repos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,4611,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_propi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,590,7632,'','".AddSlashes(pg_result($resaco,$iresaco,'r06_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rubricas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r06_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r06_anousu = $r06_anousu ";
        }
        if($r06_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r06_mesusu = $r06_mesusu ";
        }
        if($r06_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r06_codigo = '$r06_codigo' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastramento dos Codigos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r06_anousu."-".$r06_mesusu."-".$r06_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastramento dos Codigos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r06_anousu."-".$r06_mesusu."-".$r06_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r06_anousu."-".$r06_mesusu."-".$r06_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:rubricas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function atualiza_incluir (){
  	 $this->incluir($this->r06_anousu,$this->r06_mesusu,$this->r06_codigo);
   }
   function sql_query ( $r06_anousu=null,$r06_mesusu=null,$r06_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rubricas ";
     $sql .= "      inner join db_config  on  db_config.codigo = rubricas.r06_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r06_anousu!=null ){
         $sql2 .= " where rubricas.r06_anousu = $r06_anousu "; 
       } 
       if($r06_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rubricas.r06_mesusu = $r06_mesusu "; 
       } 
       if($r06_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rubricas.r06_codigo = '$r06_codigo' "; 
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
   function sql_query_file ( $r06_anousu=null,$r06_mesusu=null,$r06_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rubricas ";
     $sql2 = "";
     if($dbwhere==""){
       if($r06_anousu!=null ){
         $sql2 .= " where rubricas.r06_anousu = $r06_anousu "; 
       } 
       if($r06_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rubricas.r06_mesusu = $r06_mesusu "; 
       } 
       if($r06_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rubricas.r06_codigo = '$r06_codigo' "; 
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