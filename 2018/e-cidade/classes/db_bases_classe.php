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
//CLASSE DA ENTIDADE bases
class cl_bases { 
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
   var $r08_anousu = 0; 
   var $r08_mesusu = 0; 
   var $r08_codigo = null; 
   var $r08_descr = null; 
   var $r08_calqua = 'f'; 
   var $r08_mesant = 'f'; 
   var $r08_pfixo = 'f'; 
   var $r08_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r08_anousu = int4 = Ano do Exercicio 
                 r08_mesusu = int4 = Mes do Exercicio 
                 r08_codigo = varchar(4) = Base 
                 r08_descr = varchar(30) = Descrição da Base 
                 r08_calqua = bool = Calculo pela Quantidade (s/n) 
                 r08_mesant = bool = Pesquisa valores mes anterior 
                 r08_pfixo = bool = Calcular pelo Ponto Fixo 
                 r08_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_bases() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bases"); 
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
       $this->r08_anousu = ($this->r08_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r08_anousu"]:$this->r08_anousu);
       $this->r08_mesusu = ($this->r08_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r08_mesusu"]:$this->r08_mesusu);
       $this->r08_codigo = ($this->r08_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r08_codigo"]:$this->r08_codigo);
       $this->r08_descr = ($this->r08_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["r08_descr"]:$this->r08_descr);
       $this->r08_calqua = ($this->r08_calqua == "f"?@$GLOBALS["HTTP_POST_VARS"]["r08_calqua"]:$this->r08_calqua);
       $this->r08_mesant = ($this->r08_mesant == "f"?@$GLOBALS["HTTP_POST_VARS"]["r08_mesant"]:$this->r08_mesant);
       $this->r08_pfixo = ($this->r08_pfixo == "f"?@$GLOBALS["HTTP_POST_VARS"]["r08_pfixo"]:$this->r08_pfixo);
       $this->r08_instit = ($this->r08_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r08_instit"]:$this->r08_instit);
     }else{
       $this->r08_anousu = ($this->r08_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r08_anousu"]:$this->r08_anousu);
       $this->r08_mesusu = ($this->r08_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r08_mesusu"]:$this->r08_mesusu);
       $this->r08_codigo = ($this->r08_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r08_codigo"]:$this->r08_codigo);
       $this->r08_instit = ($this->r08_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r08_instit"]:$this->r08_instit);
     }
   }
   // funcao para inclusao
   function incluir ($r08_anousu,$r08_mesusu,$r08_codigo,$r08_instit){ 
      $this->atualizacampos();
     if($this->r08_descr == null ){ 
       $this->erro_sql = " Campo Descrição da Base nao Informado.";
       $this->erro_campo = "r08_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r08_calqua == null ){ 
       $this->erro_sql = " Campo Calculo pela Quantidade (s/n) nao Informado.";
       $this->erro_campo = "r08_calqua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r08_mesant == null ){ 
       $this->erro_sql = " Campo Pesquisa valores mes anterior nao Informado.";
       $this->erro_campo = "r08_mesant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r08_pfixo == null ){ 
       $this->erro_sql = " Campo Calcular pelo Ponto Fixo nao Informado.";
       $this->erro_campo = "r08_pfixo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r08_anousu = $r08_anousu; 
       $this->r08_mesusu = $r08_mesusu; 
       $this->r08_codigo = $r08_codigo; 
       $this->r08_instit = $r08_instit; 
     if(($this->r08_anousu == null) || ($this->r08_anousu == "") ){ 
       $this->erro_sql = " Campo r08_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r08_mesusu == null) || ($this->r08_mesusu == "") ){ 
       $this->erro_sql = " Campo r08_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r08_codigo == null) || ($this->r08_codigo == "") ){ 
       $this->erro_sql = " Campo r08_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r08_instit == null) || ($this->r08_instit == "") ){ 
       $this->erro_sql = " Campo r08_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bases(
                                       r08_anousu 
                                      ,r08_mesusu 
                                      ,r08_codigo 
                                      ,r08_descr 
                                      ,r08_calqua 
                                      ,r08_mesant 
                                      ,r08_pfixo 
                                      ,r08_instit 
                       )
                values (
                                $this->r08_anousu 
                               ,$this->r08_mesusu 
                               ,'$this->r08_codigo' 
                               ,'$this->r08_descr' 
                               ,'$this->r08_calqua' 
                               ,'$this->r08_mesant' 
                               ,'$this->r08_pfixo' 
                               ,$this->r08_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro das Bases ($this->r08_anousu."-".$this->r08_mesusu."-".$this->r08_codigo."-".$this->r08_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro das Bases já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro das Bases ($this->r08_anousu."-".$this->r08_mesusu."-".$this->r08_codigo."-".$this->r08_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r08_anousu."-".$this->r08_mesusu."-".$this->r08_codigo."-".$this->r08_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r08_anousu,$this->r08_mesusu,$this->r08_codigo,$this->r08_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3686,'$this->r08_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3687,'$this->r08_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3688,'$this->r08_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,7633,'$this->r08_instit','I')");
       $resac = db_query("insert into db_acount values($acount,530,3686,'','".AddSlashes(pg_result($resaco,0,'r08_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,530,3687,'','".AddSlashes(pg_result($resaco,0,'r08_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,530,3688,'','".AddSlashes(pg_result($resaco,0,'r08_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,530,3689,'','".AddSlashes(pg_result($resaco,0,'r08_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,530,3690,'','".AddSlashes(pg_result($resaco,0,'r08_calqua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,530,3691,'','".AddSlashes(pg_result($resaco,0,'r08_mesant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,530,3692,'','".AddSlashes(pg_result($resaco,0,'r08_pfixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,530,7633,'','".AddSlashes(pg_result($resaco,0,'r08_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r08_anousu=null,$r08_mesusu=null,$r08_codigo=null,$r08_instit=null) { 
      $this->atualizacampos();
     $sql = " update bases set ";
     $virgula = "";
     if(trim($this->r08_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r08_anousu"])){ 
       $sql  .= $virgula." r08_anousu = $this->r08_anousu ";
       $virgula = ",";
       if(trim($this->r08_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r08_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r08_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r08_mesusu"])){ 
       $sql  .= $virgula." r08_mesusu = $this->r08_mesusu ";
       $virgula = ",";
       if(trim($this->r08_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r08_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r08_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r08_codigo"])){ 
       $sql  .= $virgula." r08_codigo = '$this->r08_codigo' ";
       $virgula = ",";
       if(trim($this->r08_codigo) == null ){ 
         $this->erro_sql = " Campo Base nao Informado.";
         $this->erro_campo = "r08_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r08_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r08_descr"])){ 
       $sql  .= $virgula." r08_descr = '$this->r08_descr' ";
       $virgula = ",";
       if(trim($this->r08_descr) == null ){ 
         $this->erro_sql = " Campo Descrição da Base nao Informado.";
         $this->erro_campo = "r08_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r08_calqua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r08_calqua"])){ 
       $sql  .= $virgula." r08_calqua = '$this->r08_calqua' ";
       $virgula = ",";
       if(trim($this->r08_calqua) == null ){ 
         $this->erro_sql = " Campo Calculo pela Quantidade (s/n) nao Informado.";
         $this->erro_campo = "r08_calqua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r08_mesant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r08_mesant"])){ 
       $sql  .= $virgula." r08_mesant = '$this->r08_mesant' ";
       $virgula = ",";
       if(trim($this->r08_mesant) == null ){ 
         $this->erro_sql = " Campo Pesquisa valores mes anterior nao Informado.";
         $this->erro_campo = "r08_mesant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r08_pfixo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r08_pfixo"])){ 
       $sql  .= $virgula." r08_pfixo = '$this->r08_pfixo' ";
       $virgula = ",";
       if(trim($this->r08_pfixo) == null ){ 
         $this->erro_sql = " Campo Calcular pelo Ponto Fixo nao Informado.";
         $this->erro_campo = "r08_pfixo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r08_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r08_instit"])){ 
       $sql  .= $virgula." r08_instit = $this->r08_instit ";
       $virgula = ",";
       if(trim($this->r08_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "r08_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r08_anousu!=null){
       $sql .= " r08_anousu = $this->r08_anousu";
     }
     if($r08_mesusu!=null){
       $sql .= " and  r08_mesusu = $this->r08_mesusu";
     }
     if($r08_codigo!=null){
       $sql .= " and  r08_codigo = '$this->r08_codigo'";
     }
     if($r08_instit!=null){
       $sql .= " and  r08_instit = $this->r08_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r08_anousu,$this->r08_mesusu,$this->r08_codigo,$this->r08_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3686,'$this->r08_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3687,'$this->r08_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3688,'$this->r08_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,7633,'$this->r08_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r08_anousu"]))
           $resac = db_query("insert into db_acount values($acount,530,3686,'".AddSlashes(pg_result($resaco,$conresaco,'r08_anousu'))."','$this->r08_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r08_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,530,3687,'".AddSlashes(pg_result($resaco,$conresaco,'r08_mesusu'))."','$this->r08_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r08_codigo"]))
           $resac = db_query("insert into db_acount values($acount,530,3688,'".AddSlashes(pg_result($resaco,$conresaco,'r08_codigo'))."','$this->r08_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r08_descr"]))
           $resac = db_query("insert into db_acount values($acount,530,3689,'".AddSlashes(pg_result($resaco,$conresaco,'r08_descr'))."','$this->r08_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r08_calqua"]))
           $resac = db_query("insert into db_acount values($acount,530,3690,'".AddSlashes(pg_result($resaco,$conresaco,'r08_calqua'))."','$this->r08_calqua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r08_mesant"]))
           $resac = db_query("insert into db_acount values($acount,530,3691,'".AddSlashes(pg_result($resaco,$conresaco,'r08_mesant'))."','$this->r08_mesant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r08_pfixo"]))
           $resac = db_query("insert into db_acount values($acount,530,3692,'".AddSlashes(pg_result($resaco,$conresaco,'r08_pfixo'))."','$this->r08_pfixo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r08_instit"]))
           $resac = db_query("insert into db_acount values($acount,530,7633,'".AddSlashes(pg_result($resaco,$conresaco,'r08_instit'))."','$this->r08_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro das Bases nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r08_anousu."-".$this->r08_mesusu."-".$this->r08_codigo."-".$this->r08_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das Bases nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r08_anousu."-".$this->r08_mesusu."-".$this->r08_codigo."-".$this->r08_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r08_anousu."-".$this->r08_mesusu."-".$this->r08_codigo."-".$this->r08_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r08_anousu=null,$r08_mesusu=null,$r08_codigo=null,$r08_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r08_anousu,$r08_mesusu,$r08_codigo,$r08_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3686,'$r08_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3687,'$r08_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3688,'$r08_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,7633,'$r08_instit','E')");
         $resac = db_query("insert into db_acount values($acount,530,3686,'','".AddSlashes(pg_result($resaco,$iresaco,'r08_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,530,3687,'','".AddSlashes(pg_result($resaco,$iresaco,'r08_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,530,3688,'','".AddSlashes(pg_result($resaco,$iresaco,'r08_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,530,3689,'','".AddSlashes(pg_result($resaco,$iresaco,'r08_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,530,3690,'','".AddSlashes(pg_result($resaco,$iresaco,'r08_calqua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,530,3691,'','".AddSlashes(pg_result($resaco,$iresaco,'r08_mesant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,530,3692,'','".AddSlashes(pg_result($resaco,$iresaco,'r08_pfixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,530,7633,'','".AddSlashes(pg_result($resaco,$iresaco,'r08_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from bases
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r08_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r08_anousu = $r08_anousu ";
        }
        if($r08_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r08_mesusu = $r08_mesusu ";
        }
        if($r08_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r08_codigo = '$r08_codigo' ";
        }
        if($r08_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r08_instit = $r08_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro das Bases nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r08_anousu."-".$r08_mesusu."-".$r08_codigo."-".$r08_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das Bases nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r08_anousu."-".$r08_mesusu."-".$r08_codigo."-".$r08_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r08_anousu."-".$r08_mesusu."-".$r08_codigo."-".$r08_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:bases";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function atualiza_incluir (){
  	 $this->incluir($this->r08_anousu,$this->r08_mesusu,$this->r08_codigo);
   }
   function sql_query ( $r08_anousu=null,$r08_mesusu=null,$r08_codigo=null,$r08_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bases ";
     $sql .= "      inner join db_config  on  db_config.codigo = bases.r08_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r08_anousu!=null ){
         $sql2 .= " where bases.r08_anousu = $r08_anousu "; 
       } 
       if($r08_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " bases.r08_mesusu = $r08_mesusu "; 
       } 
       if($r08_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " bases.r08_codigo = '$r08_codigo' "; 
       } 
       if($r08_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " bases.r08_instit = $r08_instit "; 
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
   function sql_query_file ( $r08_anousu=null,$r08_mesusu=null,$r08_codigo=null,$r08_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bases ";
     $sql2 = "";
     if($dbwhere==""){
       if($r08_anousu!=null ){
         $sql2 .= " where bases.r08_anousu = $r08_anousu "; 
       } 
       if($r08_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " bases.r08_mesusu = $r08_mesusu "; 
       } 
       if($r08_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " bases.r08_codigo = '$r08_codigo' "; 
       } 
       if($r08_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " bases.r08_instit = $r08_instit "; 
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
   function sql_query_rubricas ( $r08_anousu=null,$r08_mesusu=null,$r08_codigo=null,$r08_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bases ";
     $sql .= "      inner join db_config  on  db_config.codigo = bases.r08_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join basesr  on  bases.r08_codigo = basesr.r09_base";
     $sql .= "                        and  bases.r08_anousu = basesr.r09_anousu ";
     $sql .= "                        and  bases.r08_mesusu = basesr.r09_mesusu ";
     $sql .= "                        and  bases.r08_instit = basesr.r09_instit ";
     $sql2 = "";
     if($dbwhere==""){
       if($r08_anousu!=null ){
         $sql2 .= " where bases.r08_anousu = $r08_anousu "; 
       } 
       if($r08_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " bases.r08_mesusu = $r08_mesusu "; 
       } 
       if($r08_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " bases.r08_codigo = '$r08_codigo' "; 
       } 
       if($r08_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " bases.r08_instit = $r08_instit "; 
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