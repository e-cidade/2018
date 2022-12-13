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
//CLASSE DA ENTIDADE inssirf
class cl_inssirf { 
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
   var $r33_instit = 0; 
   var $r33_codigo = 0; 
   var $r33_anousu = 0; 
   var $r33_mesusu = 0; 
   var $r33_codtab = 0; 
   var $r33_inic = 0; 
   var $r33_fim = 0; 
   var $r33_perc = 0; 
   var $r33_deduzi = 0; 
   var $r33_nome = null; 
   var $r33_tipo = null; 
   var $r33_rubmat = null; 
   var $r33_ppatro = 0; 
   var $r33_rubsau = null; 
   var $r33_rubaci = null; 
   var $r33_basfer = null; 
   var $r33_basfet = null; 
   var $r33_tinati = 0; 
   var $r33_codele = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r33_instit = int4 = Cod. Instituição 
                 r33_codigo = int8 = Código da tabela 
                 r33_anousu = int4 = Ano do Exercicio 
                 r33_mesusu = int4 = Mes do Exercicio 
                 r33_codtab = int4 = Tabela 
                 r33_inic = float8 = Valor Inicial da Faixa 
                 r33_fim = float8 = Valor Final da Faixa 
                 r33_perc = float8 = Percentual 
                 r33_deduzi = float8 = Deduzir 
                 r33_nome = varchar(15) = Tabela 
                 r33_tipo = varchar(1) = Tipo 
                 r33_rubmat = varchar(4) = Rubrica salário maternidade 
                 r33_ppatro = float8 = Percentual Previdência Patronal 
                 r33_rubsau = varchar(4) = Rubrica Licença Saúde 
                 r33_rubaci = varchar(4) = Rubrica Acidente de Trabalho 
                 r33_basfer = varchar(4) = Base Previdência Férias 
                 r33_basfet = varchar(4) = Base Previdência Férias (Total) 
                 r33_tinati = float8 = Teto para Inativos 
                 r33_codele = int4 = Código do Desdobramento 
                 ";
   //funcao construtor da classe 
   function cl_inssirf() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("inssirf"); 
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
       $this->r33_instit = ($this->r33_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_instit"]:$this->r33_instit);
       $this->r33_codigo = ($this->r33_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_codigo"]:$this->r33_codigo);
       $this->r33_anousu = ($this->r33_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_anousu"]:$this->r33_anousu);
       $this->r33_mesusu = ($this->r33_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_mesusu"]:$this->r33_mesusu);
       $this->r33_codtab = ($this->r33_codtab == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_codtab"]:$this->r33_codtab);
       $this->r33_inic = ($this->r33_inic == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_inic"]:$this->r33_inic);
       $this->r33_fim = ($this->r33_fim == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_fim"]:$this->r33_fim);
       $this->r33_perc = ($this->r33_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_perc"]:$this->r33_perc);
       $this->r33_deduzi = ($this->r33_deduzi == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_deduzi"]:$this->r33_deduzi);
       $this->r33_nome = ($this->r33_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_nome"]:$this->r33_nome);
       $this->r33_tipo = ($this->r33_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_tipo"]:$this->r33_tipo);
       $this->r33_rubmat = ($this->r33_rubmat == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_rubmat"]:$this->r33_rubmat);
       $this->r33_ppatro = ($this->r33_ppatro == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_ppatro"]:$this->r33_ppatro);
       $this->r33_rubsau = ($this->r33_rubsau == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_rubsau"]:$this->r33_rubsau);
       $this->r33_rubaci = ($this->r33_rubaci == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_rubaci"]:$this->r33_rubaci);
       $this->r33_basfer = ($this->r33_basfer == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_basfer"]:$this->r33_basfer);
       $this->r33_basfet = ($this->r33_basfet == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_basfet"]:$this->r33_basfet);
       $this->r33_tinati = ($this->r33_tinati == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_tinati"]:$this->r33_tinati);
       $this->r33_codele = ($this->r33_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_codele"]:$this->r33_codele);
     }else{
       $this->r33_instit = ($this->r33_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_instit"]:$this->r33_instit);
       $this->r33_codigo = ($this->r33_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_codigo"]:$this->r33_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($r33_codigo,$r33_instit){ 
      $this->atualizacampos();
     if($this->r33_anousu == null ){ 
       $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
       $this->erro_campo = "r33_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r33_mesusu == null ){ 
       $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
       $this->erro_campo = "r33_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r33_codtab == null ){ 
       $this->erro_sql = " Campo Tabela nao Informado.";
       $this->erro_campo = "r33_codtab";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r33_inic == null ){ 
       $this->erro_sql = " Campo Valor Inicial da Faixa nao Informado.";
       $this->erro_campo = "r33_inic";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r33_fim == null ){ 
       $this->erro_sql = " Campo Valor Final da Faixa nao Informado.";
       $this->erro_campo = "r33_fim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r33_perc == null ){ 
       $this->erro_sql = " Campo Percentual nao Informado.";
       $this->erro_campo = "r33_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r33_deduzi == null ){ 
       $this->erro_sql = " Campo Deduzir nao Informado.";
       $this->erro_campo = "r33_deduzi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r33_ppatro == null ){ 
       $this->r33_ppatro = "0";
     }
     if($this->r33_tinati == null ){ 
       $this->r33_tinati = "0";
     }
     if($this->r33_codele == null ){
     	$this->r33_codele = "null";
     }
     if($r33_codigo == "" || $r33_codigo == null ){
       $result = db_query("select nextval('inssirf_r33_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: inssirf_r33_codigo_seq do campo: r33_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->r33_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from inssirf_r33_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $r33_codigo)){
         $this->erro_sql = " Campo r33_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->r33_codigo = $r33_codigo; 
       }
     }
     if(($this->r33_codigo == null) || ($this->r33_codigo == "") ){ 
       $this->erro_sql = " Campo r33_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r33_instit == null) || ($this->r33_instit == "") ){ 
       $this->erro_sql = " Campo r33_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into inssirf(
                                       r33_instit 
                                      ,r33_codigo 
                                      ,r33_anousu 
                                      ,r33_mesusu 
                                      ,r33_codtab 
                                      ,r33_inic 
                                      ,r33_fim 
                                      ,r33_perc 
                                      ,r33_deduzi 
                                      ,r33_nome 
                                      ,r33_tipo 
                                      ,r33_rubmat 
                                      ,r33_ppatro 
                                      ,r33_rubsau 
                                      ,r33_rubaci 
                                      ,r33_basfer 
                                      ,r33_basfet 
                                      ,r33_tinati 
                                      ,r33_codele 
                       )
                values (
                                $this->r33_instit 
                               ,$this->r33_codigo 
                               ,$this->r33_anousu 
                               ,$this->r33_mesusu 
                               ,$this->r33_codtab 
                               ,$this->r33_inic 
                               ,$this->r33_fim 
                               ,$this->r33_perc 
                               ,$this->r33_deduzi 
                               ,'$this->r33_nome' 
                               ,'$this->r33_tipo' 
                               ,'$this->r33_rubmat' 
                               ,$this->r33_ppatro 
                               ,'$this->r33_rubsau' 
                               ,'$this->r33_rubaci' 
                               ,'$this->r33_basfer' 
                               ,'$this->r33_basfet' 
                               ,$this->r33_tinati 
                               ,$this->r33_codele 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro das Tabelas                               ($this->r33_codigo."-".$this->r33_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro das Tabelas                               já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro das Tabelas                               ($this->r33_codigo."-".$this->r33_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r33_codigo."-".$this->r33_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r33_codigo,$this->r33_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8826,'$this->r33_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,9894,'$this->r33_instit','I')");
       $resac = db_query("insert into db_acount values($acount,561,9894,'','".AddSlashes(pg_result($resaco,0,'r33_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,8826,'','".AddSlashes(pg_result($resaco,0,'r33_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,4009,'','".AddSlashes(pg_result($resaco,0,'r33_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,4010,'','".AddSlashes(pg_result($resaco,0,'r33_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,4011,'','".AddSlashes(pg_result($resaco,0,'r33_codtab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,4012,'','".AddSlashes(pg_result($resaco,0,'r33_inic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,4013,'','".AddSlashes(pg_result($resaco,0,'r33_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,4014,'','".AddSlashes(pg_result($resaco,0,'r33_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,4015,'','".AddSlashes(pg_result($resaco,0,'r33_deduzi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,4016,'','".AddSlashes(pg_result($resaco,0,'r33_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,4017,'','".AddSlashes(pg_result($resaco,0,'r33_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,4018,'','".AddSlashes(pg_result($resaco,0,'r33_rubmat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,4019,'','".AddSlashes(pg_result($resaco,0,'r33_ppatro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,4020,'','".AddSlashes(pg_result($resaco,0,'r33_rubsau'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,8828,'','".AddSlashes(pg_result($resaco,0,'r33_rubaci'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,4597,'','".AddSlashes(pg_result($resaco,0,'r33_basfer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,4598,'','".AddSlashes(pg_result($resaco,0,'r33_basfet'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,8830,'','".AddSlashes(pg_result($resaco,0,'r33_tinati'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,561,19145,'','".AddSlashes(pg_result($resaco,0,'r33_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r33_codigo=null,$r33_instit=null) { 
      $this->atualizacampos();
     $sql = " update inssirf set ";
     $virgula = "";
     if(trim($this->r33_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_instit"])){ 
       $sql  .= $virgula." r33_instit = $this->r33_instit ";
       $virgula = ",";
       if(trim($this->r33_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "r33_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r33_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_codigo"])){ 
       $sql  .= $virgula." r33_codigo = $this->r33_codigo ";
       $virgula = ",";
       if(trim($this->r33_codigo) == null ){ 
         $this->erro_sql = " Campo Código da tabela nao Informado.";
         $this->erro_campo = "r33_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r33_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_anousu"])){ 
       $sql  .= $virgula." r33_anousu = $this->r33_anousu ";
       $virgula = ",";
       if(trim($this->r33_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r33_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r33_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_mesusu"])){ 
       $sql  .= $virgula." r33_mesusu = $this->r33_mesusu ";
       $virgula = ",";
       if(trim($this->r33_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r33_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r33_codtab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_codtab"])){ 
       $sql  .= $virgula." r33_codtab = $this->r33_codtab ";
       $virgula = ",";
       if(trim($this->r33_codtab) == null ){ 
         $this->erro_sql = " Campo Tabela nao Informado.";
         $this->erro_campo = "r33_codtab";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r33_inic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_inic"])){ 
       $sql  .= $virgula." r33_inic = $this->r33_inic ";
       $virgula = ",";
       if(trim($this->r33_inic) == null ){ 
         $this->erro_sql = " Campo Valor Inicial da Faixa nao Informado.";
         $this->erro_campo = "r33_inic";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r33_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_fim"])){ 
       $sql  .= $virgula." r33_fim = $this->r33_fim ";
       $virgula = ",";
       if(trim($this->r33_fim) == null ){ 
         $this->erro_sql = " Campo Valor Final da Faixa nao Informado.";
         $this->erro_campo = "r33_fim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r33_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_perc"])){ 
       $sql  .= $virgula." r33_perc = $this->r33_perc ";
       $virgula = ",";
       if(trim($this->r33_perc) == null ){ 
         $this->erro_sql = " Campo Percentual nao Informado.";
         $this->erro_campo = "r33_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r33_deduzi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_deduzi"])){ 
       $sql  .= $virgula." r33_deduzi = $this->r33_deduzi ";
       $virgula = ",";
       if(trim($this->r33_deduzi) == null ){ 
         $this->erro_sql = " Campo Deduzir nao Informado.";
         $this->erro_campo = "r33_deduzi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r33_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_nome"])){ 
       $sql  .= $virgula." r33_nome = '$this->r33_nome' ";
       $virgula = ",";
     }
     if(trim($this->r33_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_tipo"])){ 
       $sql  .= $virgula." r33_tipo = '$this->r33_tipo' ";
       $virgula = ",";
     }
     if(trim($this->r33_rubmat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_rubmat"])){ 
       $sql  .= $virgula." r33_rubmat = '$this->r33_rubmat' ";
       $virgula = ",";
     }
     if(trim($this->r33_ppatro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_ppatro"])){ 
        if(trim($this->r33_ppatro)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r33_ppatro"])){ 
           $this->r33_ppatro = "0" ; 
        } 
       $sql  .= $virgula." r33_ppatro = $this->r33_ppatro ";
       $virgula = ",";
     }
     if(trim($this->r33_rubsau)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_rubsau"])){ 
       $sql  .= $virgula." r33_rubsau = '$this->r33_rubsau' ";
       $virgula = ",";
     }
     if(trim($this->r33_rubaci)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_rubaci"])){ 
       $sql  .= $virgula." r33_rubaci = '$this->r33_rubaci' ";
       $virgula = ",";
     }
     if(trim($this->r33_basfer)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_basfer"])){ 
       $sql  .= $virgula." r33_basfer = '$this->r33_basfer' ";
       $virgula = ",";
     }
     if(trim($this->r33_basfet)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_basfet"])){ 
       $sql  .= $virgula." r33_basfet = '$this->r33_basfet' ";
       $virgula = ",";
     }
     if(trim($this->r33_tinati)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_tinati"])){ 
        if(trim($this->r33_tinati)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r33_tinati"])){ 
           $this->r33_tinati = "0" ; 
        } 
       $sql  .= $virgula." r33_tinati = $this->r33_tinati ";
       $virgula = ",";
     }
     if(trim($this->r33_codele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_codele"])){ 
        if(trim($this->r33_codele)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r33_codele"])){ 
           $this->r33_codele = "null" ; 
        } 
       $sql  .= $virgula." r33_codele = $this->r33_codele ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($r33_codigo!=null){
       $sql .= " r33_codigo = $this->r33_codigo";
     }
     if($r33_instit!=null){
       $sql .= " and  r33_instit = $this->r33_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r33_codigo,$this->r33_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8826,'$this->r33_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,9894,'$this->r33_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_instit"]) || $this->r33_instit != "")
           $resac = db_query("insert into db_acount values($acount,561,9894,'".AddSlashes(pg_result($resaco,$conresaco,'r33_instit'))."','$this->r33_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_codigo"]) || $this->r33_codigo != "")
           $resac = db_query("insert into db_acount values($acount,561,8826,'".AddSlashes(pg_result($resaco,$conresaco,'r33_codigo'))."','$this->r33_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_anousu"]) || $this->r33_anousu != "")
           $resac = db_query("insert into db_acount values($acount,561,4009,'".AddSlashes(pg_result($resaco,$conresaco,'r33_anousu'))."','$this->r33_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_mesusu"]) || $this->r33_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,561,4010,'".AddSlashes(pg_result($resaco,$conresaco,'r33_mesusu'))."','$this->r33_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_codtab"]) || $this->r33_codtab != "")
           $resac = db_query("insert into db_acount values($acount,561,4011,'".AddSlashes(pg_result($resaco,$conresaco,'r33_codtab'))."','$this->r33_codtab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_inic"]) || $this->r33_inic != "")
           $resac = db_query("insert into db_acount values($acount,561,4012,'".AddSlashes(pg_result($resaco,$conresaco,'r33_inic'))."','$this->r33_inic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_fim"]) || $this->r33_fim != "")
           $resac = db_query("insert into db_acount values($acount,561,4013,'".AddSlashes(pg_result($resaco,$conresaco,'r33_fim'))."','$this->r33_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_perc"]) || $this->r33_perc != "")
           $resac = db_query("insert into db_acount values($acount,561,4014,'".AddSlashes(pg_result($resaco,$conresaco,'r33_perc'))."','$this->r33_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_deduzi"]) || $this->r33_deduzi != "")
           $resac = db_query("insert into db_acount values($acount,561,4015,'".AddSlashes(pg_result($resaco,$conresaco,'r33_deduzi'))."','$this->r33_deduzi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_nome"]) || $this->r33_nome != "")
           $resac = db_query("insert into db_acount values($acount,561,4016,'".AddSlashes(pg_result($resaco,$conresaco,'r33_nome'))."','$this->r33_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_tipo"]) || $this->r33_tipo != "")
           $resac = db_query("insert into db_acount values($acount,561,4017,'".AddSlashes(pg_result($resaco,$conresaco,'r33_tipo'))."','$this->r33_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_rubmat"]) || $this->r33_rubmat != "")
           $resac = db_query("insert into db_acount values($acount,561,4018,'".AddSlashes(pg_result($resaco,$conresaco,'r33_rubmat'))."','$this->r33_rubmat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_ppatro"]) || $this->r33_ppatro != "")
           $resac = db_query("insert into db_acount values($acount,561,4019,'".AddSlashes(pg_result($resaco,$conresaco,'r33_ppatro'))."','$this->r33_ppatro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_rubsau"]) || $this->r33_rubsau != "")
           $resac = db_query("insert into db_acount values($acount,561,4020,'".AddSlashes(pg_result($resaco,$conresaco,'r33_rubsau'))."','$this->r33_rubsau',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_rubaci"]) || $this->r33_rubaci != "")
           $resac = db_query("insert into db_acount values($acount,561,8828,'".AddSlashes(pg_result($resaco,$conresaco,'r33_rubaci'))."','$this->r33_rubaci',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_basfer"]) || $this->r33_basfer != "")
           $resac = db_query("insert into db_acount values($acount,561,4597,'".AddSlashes(pg_result($resaco,$conresaco,'r33_basfer'))."','$this->r33_basfer',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_basfet"]) || $this->r33_basfet != "")
           $resac = db_query("insert into db_acount values($acount,561,4598,'".AddSlashes(pg_result($resaco,$conresaco,'r33_basfet'))."','$this->r33_basfet',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_tinati"]) || $this->r33_tinati != "")
           $resac = db_query("insert into db_acount values($acount,561,8830,'".AddSlashes(pg_result($resaco,$conresaco,'r33_tinati'))."','$this->r33_tinati',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r33_codele"]) || $this->r33_codele != "")
           $resac = db_query("insert into db_acount values($acount,561,19145,'".AddSlashes(pg_result($resaco,$conresaco,'r33_codele'))."','$this->r33_codele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro das Tabelas                               nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r33_codigo."-".$this->r33_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das Tabelas                               nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r33_codigo."-".$this->r33_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r33_codigo."-".$this->r33_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r33_codigo=null,$r33_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r33_codigo,$r33_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8826,'$r33_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,9894,'$r33_instit','E')");
         $resac = db_query("insert into db_acount values($acount,561,9894,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,8826,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,4009,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,4010,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,4011,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_codtab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,4012,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_inic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,4013,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,4014,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,4015,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_deduzi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,4016,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,4017,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,4018,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_rubmat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,4019,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_ppatro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,4020,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_rubsau'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,8828,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_rubaci'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,4597,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_basfer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,4598,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_basfet'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,8830,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_tinati'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,561,19145,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from inssirf
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r33_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r33_codigo = $r33_codigo ";
        }
        if($r33_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r33_instit = $r33_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro das Tabelas                               nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r33_codigo."-".$r33_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das Tabelas                               nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r33_codigo."-".$r33_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r33_codigo."-".$r33_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:inssirf";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $r33_codigo=null,$r33_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inssirf ";
     $sql .= "      inner join db_config  on  db_config.codigo = inssirf.r33_instit";
     $sql .= "      left  join orcelemento  on  orcelemento.o56_codele = inssirf.r33_codele and  orcelemento.o56_anousu = inssirf.r33_anousu";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($r33_codigo!=null ){
         $sql2 .= " where inssirf.r33_codigo = $r33_codigo "; 
       } 
       if($r33_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " inssirf.r33_instit = $r33_instit "; 
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
   function sql_query_file ( $r33_codigo=null,$r33_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inssirf ";
     $sql2 = "";
     if($dbwhere==""){
       if($r33_codigo!=null ){
         $sql2 .= " where inssirf.r33_codigo = $r33_codigo "; 
       } 
       if($r33_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " inssirf.r33_instit = $r33_instit "; 
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
   function atualiza_incluir (){
  	 $this->incluir($this->r33_anousu,$this->r33_mesusu,$this->r33_codtab);
   }
   /**                                                                                                                                                                
 * sql_query_dados                                                                                                                                                 
 */                                                                                                                                                                
function sql_query_dados ($r33_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {                                                                       
                                                                                                                                                                   
	$sql = "select ";                                                                                                                                                 
                                                                                                                                                                   
	if ($campos != "*" ) {                                                                                                                                            
                                                                                                                                                                   
		$campos_sql = split("#", $campos);                                                                                                                              
		$virgula    = "";                                                                                                                                               
                                                                                                                                                                   
		for ( $i=0; $i < sizeof($campos_sql); $i++ ) {                                                                                                                  
                                                                                                                                                                   
			$sql .= $virgula.$campos_sql[$i];                                                                                                                             
			$virgula = ",";                                                                                                                                               
		}                                                                                                                                                               
                                                                                                                                                                   
	} else {                                                                                                                                                          
		$sql .= $campos;                                                                                                                                                
	}                                                                                                                                                                 
                                                                                                                                                                   
	$sql .= " from inssirf ";                                                                                                                                         
	$sql .= "      left join rhrubricas a on a.rh27_rubric = inssirf.r33_rubmat and a.rh27_instit =  inssirf.r33_instit ";                                            
	$sql .= "      left join rhrubricas b on b.rh27_rubric = inssirf.r33_rubsau and b.rh27_instit =  inssirf.r33_instit ";                                            
	$sql .= "      left join rhrubricas c on c.rh27_rubric = inssirf.r33_rubaci and c.rh27_instit =  inssirf.r33_instit ";                                            
	$sql .= "      left join bases      d on d.r08_codigo  = inssirf.r33_basfer                                         ";                                            
	$sql .= "                             and d.r08_instit = inssirf.r33_instit                                         ";                                            
	$sql .= "                             and d.r08_anousu = ".db_anofolha()."                                          ";                                            
	$sql .= "                             and d.r08_mesusu = ".db_mesfolha()."                                          ";                                            
	$sql .= "      left join bases      e on e.r08_codigo  = inssirf.r33_basfet                                         ";                                            
	$sql .= "                             and d.r08_instit = inssirf.r33_instit                                         ";                                            
	$sql .= "                             and d.r08_anousu = ".db_anofolha()."                                          ";                                            
	$sql .= "                             and d.r08_mesusu = ".db_mesfolha()."                                          ";                                            
	$sql .= "      left join orcelemento on orcelemento.o56_codele = inssirf.r33_codele                                 ";                                            
	$sql .= "			                     and orcelemento.o56_anousu = ".db_anofolha()."                                  ";                                             
                                                                                                                                                                   
	$sql2 = "";                                                                                                                                                       
                                                                                                                                                                   
	if ( $dbwhere == "" ) {                                                                                                                                           
                                                                                                                                                                   
		if ( $r33_codigo != null ){                                                                                                                                     
			$sql2 .= " where inssirf.r33_codigo = $r33_codigo ";                                                                                                          
		}                                                                                                                                                               
                                                                                                                                                                   
	} elseif ( $dbwhere != "" ) {                                                                                                                                     
		$sql2 = " where $dbwhere";                                                                                                                                      
	}                                                                                                                                                                 
                                                                                                                                                                   
	$sql .= $sql2;                                                                                                                                                    
                                                                                                                                                                   
	if ( $ordem != null ) {                                                                                                                                           
                                                                                                                                                                   
		$sql       .= " order by ";                                                                                                                                     
		$campos_sql = split("#",$ordem);                                                                                                                                
		$virgula    = "";                                                                                                                                               
                                                                                                                                                                   
		for ( $i = 0; $i < sizeof($campos_sql); $i++ ) {                                                                                                                
                                                                                                                                                                   
			$sql    .= $virgula.$campos_sql[$i];                                                                                                                          
			$virgula = ",";                                                                                                                                               
		}                                                                                                                                                               
	}                                                                                                                                                                 
                                                                                                                                                                   
	return $sql;                                                                                                                                                      
}

  /**
   * Retorna percentual patronal 
   * 
   * @param integer $iAno 
   * @param integer $iMes 
   * @param integer $iInstituicao 
   * @access public
   * @return stdClass
   */
  public function getPercentuaisPatronais($iAno, $iMes, $iInstituicao = null ) {
    
    $iInstituicao = empty($iInstituicao) ? db_getsession("DB_instit") : $iInstituicao;

    /**
     * Monta SQL dos Valores Patronais
     */
    $sValoresPatronais  = " select distinct                     ";
    $sValoresPatronais .= "        r33_codtab,                  ";
    $sValoresPatronais .= "        r33_nome,                    ";
    $sValoresPatronais .= "        r33_ppatro                   ";
    $sValoresPatronais .= "   from inssirf                      ";
    $sValoresPatronais .= "  where r33_anousu = {$iAno}         ";
    $sValoresPatronais .= "    and r33_mesusu = {$iMes}         ";
    $sValoresPatronais .= "    and r33_codtab > 2               ";
    $sValoresPatronais .= "    and r33_instit = {$iInstituicao} ";

    $rsValoresPatronais    = db_query($sValoresPatronais);
    $iRowsValoresPatronais = pg_num_rows($rsValoresPatronais);

    if( !$rsValoresPatronais || $iRowsValoresPatronais == 0 ) {
      throw new BusinessException("Sem Valores Patronais Configurados!");
    }

    /**
     * Valores padrão para base dos valores patronais
     */
    $oValoresPatronais = new stdClass();
    $oValoresPatronais->aBasePrevidencia1 = (object) array("sNome" => "BASE PREV.1", "nValor" => 0);
    $oValoresPatronais->aBasePrevidencia2 = (object) array("sNome" => "BASE PREV.2", "nValor" => 0);
    $oValoresPatronais->aBasePrevidencia3 = (object) array("sNome" => "BASE PREV.3", "nValor" => 0);
    $oValoresPatronais->aBasePrevidencia4 = (object) array("sNome" => "BASE PREV.4", "nValor" => 0);

    $aValoresPatronais = db_utils::getColectionByRecord($rsValoresPatronais);

    foreach ($aValoresPatronais as $oRowValPatronais) {

      switch ($oRowValPatronais->r33_codtab) {
     
        case 3:
        
          $oValoresPatronais->aBasePrevidencia1->sNome  = $oRowValPatronais->r33_nome;
          $oValoresPatronais->aBasePrevidencia1->nValor = $oRowValPatronais->r33_ppatro;
          break;
        case 4:
        
          $oValoresPatronais->aBasePrevidencia2->sNome  = $oRowValPatronais->r33_nome;
          $oValoresPatronais->aBasePrevidencia2->nValor = $oRowValPatronais->r33_ppatro;
          break;
        case 5:
        
          $oValoresPatronais->aBasePrevidencia3->sNome  = $oRowValPatronais->r33_nome;
          $oValoresPatronais->aBasePrevidencia3->nValor = $oRowValPatronais->r33_ppatro;
          break;
        case 6:
        
          $oValoresPatronais->aBasePrevidencia4->sNome  = $oRowValPatronais->r33_nome;
          $oValoresPatronais->aBasePrevidencia4->nValor = $oRowValPatronais->r33_ppatro;
          break;
      }
    }

    return $oValoresPatronais;
  }

}
?>